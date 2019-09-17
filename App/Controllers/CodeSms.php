<?php

namespace App\Controllers;

use App\Config;
use App\Helper;
use App\Models\LockedPhone;
use App\Models\RememberedClient;
use App\Models\Shop;
use App\PlainRule;
use App\SiteInfo;
use App\SMS;
use App\TelegramClientBot;
use Core\Controller;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CodeSms.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers
 */
class CodeSms extends Controller
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
     * Checks if the token is valid and phone is exists.
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

        if (! $this->remembered_client->getPhone() || $this->remembered_client->isTokenExpired()) {
            $this->session->getFlashBag()->add('error', 'Номер телефона не введён или срок сессии истёк.');

            $response = $this->redirect($this->getAbsUrl('/error'));
            $response->send();

            return false;
        }

        if (LockedPhone::isLocked($this->remembered_client->getPhone())) {
            $this->session->getFlashBag()->add(
                'error',
                'Вы 10 раз ввели неверный код. Ваш номер заблокирован на сутки.'
            );

            $response = $this->redirect($this->getAbsUrl('/error'));
            $response->send();

            return false;
        }

        if ($this->remembered_client->getIsVerified()) {
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

        return $this->render('CodeSms/index.twig', [
            'title'                => 'Код из смс',
            'body_class'           => 'body_code-sms',
            'phone_number'         => SiteInfo::PHONE,
            'phone_link'           => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number'  => SiteInfo::SECOND_PHONE,
            'second_phone_link'    => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'            => SiteInfo::WORK_TIME,
            'terms_link'           => $this->getAbsUrl('/documents/agreements.pdf'),
            'form_action'          => $this->getAbsUrl('/code-sms/check-code'),
            'link_action'          => $this->getAbsUrl('/code-sms/get-code'),
            'timer_end'            => $timer_end,
            'is_test_mode_enabled' => $this->remembered_client->getIsTestModeEnabled(),
        ], $response);
    }

    /**
     * Checks sms-code (Ajax).
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function checkCodeAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateForm()) {
            return $this->sendJsonResponse(['errors' => $this->errors]);
        }

        if (! $this->remembered_client->checkCode($this->http_request->request->get('sms_code'))) {
            return $this->sendJsonResponse(['errors' => $this->remembered_client->getErrors()]);
        }

        if (! $this->remembered_client->verifyPhone()) {
            return $this->sendJsonResponse(['errors' => $this->remembered_client->getErrors()]);
        }

        $this->maybeSendAboutClientConfirmedPhone();

        $response = new JsonResponse(['redirect' => ['url' => $this->getAbsUrl('/profile-client')]], 200);
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

        if (! $this->remembered_client->regenerateSmsCode()) {
            return $this->sendJsonResponse([
                'message' => ['body' => 'Не удалось создать код, попробуйте ещё раз.', 'type' => 'error'],
            ]);
        }

        if (! SMS::send($this->remembered_client->getPhone(), $this->remembered_client->getSmsCode())) {
            return $this->sendJsonResponse([
                'message' => ['body' => 'Не удалось отправить код, попробуйте ещё раз.', 'type' => 'error'],
            ]);
        }

        return $this->sendJsonResponse(['message' => ['body' => 'Новый код отправлен.', 'type' => 'success']]);
    }

    /**
     * Validates the form.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     */
    private function validateForm(): bool
    {
        $validator = new Validator([
            'required' => ':attribute — обязательное поле.',
            'regex'    => ':attribute — поле имеет некорректный формат.',
        ]);

        $validator->addValidator('plain', new PlainRule());

        $validation = $validator->make($this->http_request->request->all(), [
            'sms_code' => 'required|regex:/^\d{4}$/',
            'terms'    => 'required|plain',
        ]);

        $validation->setAliases([
            'sms_code' => 'СМС-код',
            'terms'    => 'Условия',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->errors = $validation->errors()->all();

            return false;
        }

        return true;
    }

    /**
     * Sends the message by Telegram.
     *
     * @return void
     * @throws \Exception
     */
    private function maybeSendAboutClientConfirmedPhone(): void
    {
        if (Config::isDevServer()) {
            return;
        }

        $telegram_client_bot = new TelegramClientBot();

        /* @var Shop $shop */
        $shop = Shop::findById($this->remembered_client->getShopId());

        $telegram_client_bot->clientConfirmedPhoneNumber(
            $this->remembered_client->getPhone(),
            $shop->getName(),
            $this->remembered_client->getOrderPrice()
        );
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
