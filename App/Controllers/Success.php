<?php

namespace App\Controllers;

use App\Crediting;
use App\Helper;
use App\Models\MFI;
use App\Models\RememberedClient;
use App\Models\Request;
use App\Models\Shop;
use App\SiteInfo;
use Core\Controller;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Success.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers
 */
class Success extends Controller
{
    /**
     * Errors.
     *
     * @var array
     */
    private $errors;

    /**
     * The remembered client model.
     *
     * @var RememberedClient
     */
    private $remembered_client;

    /**
     * The request model.
     *
     * @var Request
     */
    private $request;

    /**
     * Checks if the token is valid, phone is verified and status of request.
     *
     * @return bool
     * @throws \Exception
     */
    protected function before(): bool
    {
        parent::before();

        if (! $this->remembered_client = RememberedClient::findByToken($this->getRememberedClientToken())) {
            $this->session->getFlashBag()->add('error', 'Ваш токен не найден.');

            $response = $this->redirect($this->getAbsUrl('/error'));
            $response->send();

            return false;
        }

        if (! $this->remembered_client->getIsVerified() || $this->remembered_client->isTokenExpired()) {
            $this->session->getFlashBag()->add(
                'error',
                'Номер телефона не подтверждён или срок сессии истёк.'
            );

            $response = $this->redirect($this->getAbsUrl('/error'));
            $response->send();

            return false;
        }

        $this->request = Request::findByOrderIdInShop(
            $this->remembered_client->getShopId(),
            $this->remembered_client->getOrderId()
        );

        if (! $this->request || $this->request->getStatus() !== 'approved') {
            $response = $this->redirect($this->getAbsUrl('/profile-client'));
            $response->send();

            return false;
        }

        return true;
    }

    /**
     * Shows the index page.
     *
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(): Response
    {
        $timer_end = $this->getConfirmTimerEnd();

        $response = new Response();

        if (! $this->http_request->cookies->has('confirm_timer')) {
            $response->headers->setCookie(
                Cookie::create('confirm_timer', $timer_end, time() + 60 * 60 * 1, '/')
            );
        }

        $mfi_response = json_decode($this->request->getApprovedMfiResponse(), true);
        $mfi          = MFI::getById($this->request->getApprovedMfiId());

        return $this->render('Success/index.twig', [
            'title'                   => 'Вам одобрили кредит',
            'body_class'              => 'body_success',
            'phone_number'            => SiteInfo::PHONE,
            'phone_link'              => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number'     => SiteInfo::SECOND_PHONE,
            'second_phone_link'       => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'               => SiteInfo::WORK_TIME,
            'form_action'             => $this->getAbsUrl('/success/confirm-application'),
            'link_action'             => $this->getAbsUrl('/success/get-code'),
            'timer_end'               => $timer_end,
            'mfi_name'                => $mfi['name'],
            'loan_terms_link'         => $mfi_response['loan_terms_link'],
            'loan_period'             => $mfi_response['loan_period'] / 30,
            'loan_body'               => $mfi_response['loan_body'],
            'loan_amount'             => $mfi_response['loan_body'] + $mfi_response['loan_cost'],
            'loan_cost'               => $mfi_response['loan_cost'],
            'loan_daily_percent_rate' => $mfi_response['loan_daily_percent_rate'] . '%',
            'loan_daily_amount'       => $mfi_response['loan_body'] * $mfi_response['loan_daily_percent_rate'],
            'is_test_mode_enabled'    => $this->remembered_client->getIsTestModeEnabled(),
            'static_loan_terms'       => MFI::getMFITerms($mfi['id']),
        ], $response);
    }

    /**
     * Confirms application (Ajax).
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function confirmApplicationAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateForm()) {
            return $this->sendJsonResponse(['errors' => $this->errors]);
        }

        $sms_code = $this->http_request->request->get('sms_code');

        $crediting = new Crediting($this->request->getId());
        $status    = $crediting->confirmByClient($sms_code);

        if ($status === 'wrong_sms_code') {
            return $this->sendJsonResponse([
                'message' => [
                    'body' => 'Код не подходит.',
                    'type' => 'error'
                ]
            ]);
        } elseif ($status === 'data_not_found') {
            return $this->sendJsonResponse([
                'message' => [
                    'body' => 'Данные по заявке в кредитной организации не найдены.',
                    'type' => 'error'
                ]
            ]);
        } elseif (in_array($status, ['issued', 'issued_postponed'])) {
            $response = new JsonResponse(['redirect' => ['url' => $this->getCallbackUrl($status)]], 200);
            $response->headers->clearCookie('confirm_timer');

            return $response;
        }

        return $this->sendJsonResponse([
            'message' => [
                'body' => 'Произошла ошибка, попробуйте ещё раз.',
                'type' => 'error'
            ]
        ]);
    }

    /**
     * Cancels the credit application (Ajax).
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function cancelCreditApplicationAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        $crediting = new Crediting($this->request->getId());

        if (! $crediting->cancelByClient()) {
            return $this->sendJsonResponse(['message' => ['body' => 'Не удалось отменить заявку.', 'type' => 'error']]);
        }

        $response = new JsonResponse(['redirect' => ['url' => $this->getCallbackUrl('canceled')]], 200);
        $response->headers->clearCookie('confirm_timer');

        return $response;
    }

    /**
     * Gets new sms-code (Ajax).
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getCodeAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        $crediting = new Crediting($this->request->getId());

        if (! $crediting->sendCode()) {
            return $this->sendJsonResponse([
                'message' => ['body' => 'Произошла ошибка, попробуйте ещё раз.', 'type' => 'error'],
            ]);
        }

        return $this->sendJsonResponse(['message' => ['body' => 'Новый код отправлен.', 'type' => 'success']]);
    }

    /**
     * Validates the form.
     *
     * @return bool
     */
    private function validateForm(): bool
    {
        $validator = new Validator([
            'required' => ':attribute — обязательное поле.',
            'regex'    => ':attribute — поле имеет некорректный формат.',
        ]);

        $validation = $validator->make($this->http_request->request->all(), [
            'sms_code' => 'required|regex:/^\d{4}$/',
        ]);

        $validation->setAliases([
            'sms_code' => 'СМС-код',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->errors = $validation->errors()->all();

            return false;
        }

        return true;
    }

    /**
     * Gets the callback url.
     *
     * @param string $status
     * @return string
     */
    private function getCallbackUrl(string $status): string
    {
        /* @var $shop Shop */
        $shop = Shop::findById($this->remembered_client->getShopId());

        $callback_url = $this->request->getCallbackUrlWithParameters(
            $status,
            $this->remembered_client->getOrderId(),
            $shop->getSecretKey(),
            $this->remembered_client->getCallbackUrl()
        );

        return $callback_url;
    }

    /**
     * Gets the confirm timer end.
     *
     * @return int
     */
    private function getConfirmTimerEnd(): int
    {
        if ($this->http_request->cookies->has('confirm_timer')) {
            $timer_end = $this->http_request->cookies->get('confirm_timer');
        } else {
            $timer_end = time() + 180;
        }

        return $timer_end;
    }

    /**
     * Gets the remembered client token.
     *
     * @return string
     */
    private function getRememberedClientToken(): string
    {
        return $this->http_request->cookies->get('remembered_client', '');
    }
}
