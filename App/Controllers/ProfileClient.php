<?php

namespace App\Controllers;

use App\Config;
use App\Crediting;
use App\DateRule;
use App\Helper;
use App\Models\Client;
use App\Models\MFI;
use App\Models\Order;
use App\Models\RememberedClient;
use App\Models\Request;
use App\Models\Shop;
use App\PlainRule;
use App\SiteInfo;
use App\TelegramClientBot;
use Core\Controller;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProfileClient.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers
 */
class ProfileClient extends Controller
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
     * The client model.
     *
     * @var Client
     */
    private $client;

    /**
     * The order model.
     *
     * @var Order
     */
    private $order;

    /**
     * The request model.
     *
     * @var Request
     */
    private $request;

    /**
     * Checks if the token is valid and phone is verified.
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

        $this->client = Client::findByPhone($this->remembered_client->getPhone());

        return true;
    }

    /**
     * Shows the index page.
     *
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function indexAction(): Response
    {
        if ($this->request && $this->request->getStatus() === 'declined') {
            return $this->redirect($this->getAbsUrl('/declined'));
        }

        if ($this->request && $this->request->getStatus() === 'approved') {
            return $this->redirect($this->getAbsUrl('/success'));
        }

        $args = [
            'title'                => 'Анкета покупателя',
            'body_class'           => 'body_profile',
            'phone_number'         => SiteInfo::PHONE,
            'phone_link'           => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number'  => SiteInfo::SECOND_PHONE,
            'second_phone_link'    => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'            => SiteInfo::WORK_TIME,
            'form_action'          => $this->getAbsUrl('/profile-client/create-request'),
            'is_test_mode_enabled' => $this->remembered_client->getIsTestModeEnabled(),
        ];

        if ($this->request && $this->request->getStatus() === 'manual') {
            $args['modal_no_response_open'] = true;
        }

        if ($this->request && $this->request->getStatus() === 'pending') {
            $args['modal_send_request_open'] = true;
            $args['timer_enable']            = true;
            $args['timer_end']               = $this->getTimerEnd();
            $args['progressbar_end_after']   = $this->getRequestMaxTime();
            $args['ajax']['action']          = $this->getAbsUrl('/profile-client/check-request');
            $args['ajax']['timeout']         = 10;
            $args['ajax']['processData']     = true;
            $args['ajax']['contentType']     = 'application/x-www-form-urlencoded; charset=UTF-8';
        }

        if ($this->client) {
            $args['client']             = $this->client->getDataForProfile();
            $args['is_photos_required'] = false;
        } else {
            $args['client']             = ['phone' => $this->remembered_client->getPhone()];
            $args['is_photos_required'] = true;
        }

        return $this->render('ProfileClient/index.twig', $args);
    }

    /**
     * Creates a request (Ajax).
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function createRequestAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateForm()) {
            return $this->sendJsonResponse(['errors' => $this->errors]);
        }

        if (! $this->maybeCreateClient()) {
            return $this->sendJsonResponse(['errors' => $this->errors]);
        }

        if (! $this->maybeCreateOrder()) {
            return $this->sendJsonResponse(['errors' => $this->errors]);
        }

        if (! $this->createRequest()) {
            return $this->sendJsonResponse(['errors' => $this->errors]);
        }

        $this->maybeSendAboutClientCreatedRequest();

        $this->startCrediting();

        return $this->sendJsonResponse([
            'openModal' => [
                'id' => '#js-modalSendRequest',
            ],
            'timer'     => [
                'id'          => '#js-profileClientTimer',
                'end'         => $this->getTimerEnd(),
                'progressBar' => [
                    'id'       => '#js-profileClientProgressBar',
                    'endAfter' => $this->getRequestMaxTime(),
                ],
            ],
            'ajax'      => [
                'action'      => $this->getAbsUrl('/profile-client/check-request'),
                'timeout'     => 10,
                'processData' => true,
                'contentType' => 'application/x-www-form-urlencoded; charset=UTF-8',
            ],
        ]);
    }

    /**
     * Checks a request (Ajax).
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function checkRequestAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        switch ($this->request->getStatus()) {
            case 'declined':
                return $this->redirect($this->getAbsUrl('/declined'));
            case 'approved':
                return $this->redirect($this->getAbsUrl('/success'));
            case 'manual':
                return $this->sendJsonResponse([
                    'openModal'  => ['id' => '#js-modalNoResponse'],
                    'closeModal' => ['id' => '#js-modalSendRequest'],
                ]);
            case 'pending':
            default:
                return $this->sendJsonResponse([
                    'ajax' => [
                        'action'      => $this->getAbsUrl('/profile-client/check-request'),
                        'timeout'     => ($this->getTimerEnd() - time()) >= 10 ? 10 : 5,
                        'processData' => true,
                        'contentType' => 'application/x-www-form-urlencoded; charset=UTF-8',
                    ],
                ]);
        }
    }

    /**
     * Cancels a request (Ajax).
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function cancelRequestAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        $crediting = new Crediting($this->request->getId());

        if (! $crediting->cancelByClient()) {
            return $this->sendJsonResponse(['message' => ['body' => 'Не удалось отменить заявку.', 'type' => 'error']]);
        }

        return $this->redirect($this->getCallbackUrl('canceled'));
    }

    /**
     * Waits a response (Ajax).
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function waitResponseAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        // Forbids to start the process of checking the limit for already the same application.
        if ($this->request->getStatus() !== 'manual') {
            return $this->sendJsonResponse(['message' => ['body' => 'Нельзя выполнить действие.', 'type' => 'error']]);
        }

        $this->request->updateStatus('waiting_for_limit');

        $this->startWaitingForLimit();

        $this->maybeSendAboutClientWaitingForLimit();

        return $this->redirect($this->getCallbackUrl('manual'));
    }


    /**
     * Maybe creates the client.
     *
     * @return bool
     * @throws \Exception
     */
    private function maybeCreateClient(): bool
    {
        $args = [
            'last_name'              => trim($this->http_request->request->get('last_name')),
            'first_name'             => trim($this->http_request->request->get('first_name')),
            'middle_name'            => trim($this->http_request->request->get('middle_name')),
            'birth_date'             => trim($this->http_request->request->get('birth_date')),
            'birth_place'            => trim($this->http_request->request->get('birth_place')),
            'sex'                    => trim($this->http_request->request->get('sex')),
            'is_last_name_changed'   => $this->http_request->request->has('is_last_name_changed') ? 1 : 0,
            'previous_last_name'     => trim($this->http_request->request->get('previous_last_name')),
            'tin'                    => trim($this->http_request->request->get('tin')),
            'snils'                  => trim($this->http_request->request->get('snils')),
            'passport_number'        => trim($this->http_request->request->get('passport_number')),
            'passport_division_code' => trim($this->http_request->request->get('passport_division_code')),
            'passport_issued_by'     => trim($this->http_request->request->get('passport_issued_by')),
            'passport_issued_date'   => trim($this->http_request->request->get('passport_issued_date')),
            'workplace'              => trim($this->http_request->request->get('workplace')),
            'salary'                 => trim($this->http_request->request->get('salary')),
            'reg_zip_code'           => trim($this->http_request->request->get('reg_zip_code')),
            'reg_city'               => trim($this->http_request->request->get('reg_city')),
            'reg_street'             => trim($this->http_request->request->get('reg_street')),
            'reg_building'           => trim($this->http_request->request->get('reg_building')),
            'reg_apartment'          => trim($this->http_request->request->get('reg_apartment')),
            'is_address_matched'     => $this->http_request->request->has('is_address_matched') ? 1 : 0,
            'fact_zip_code'          => trim($this->http_request->request->get('fact_zip_code')),
            'fact_city'              => trim($this->http_request->request->get('fact_city')),
            'fact_street'            => trim($this->http_request->request->get('fact_street')),
            'fact_building'          => trim($this->http_request->request->get('fact_building')),
            'fact_apartment'         => trim($this->http_request->request->get('fact_apartment')),
            'phone'                  => $this->remembered_client->getPhone(),
            'additional_phone'       => trim($this->http_request->request->get('additional_phone')),
            'email'                  => trim($this->http_request->request->get('email')),

            'photo_passport_main_spread'                  =>
                $this->http_request->files->get('photo_passport_main_spread'),
            'photo_client_face_with_passport_main_spread' =>
                $this->http_request->files->get('photo_client_face_with_passport_main_spread'),
        ];

        if (! $this->client) {
            $this->client = new Client($args);
            $this->client->create();
        } else {
            $this->client->update($args);
        }

        if (! empty($this->client->getErrors())) {
            $this->errors = $this->client->getErrors();

            return false;
        }

        return true;
    }

    /**
     * Maybe creates the order.
     *
     * @return bool
     * @throws \Exception
     */
    private function maybeCreateOrder(): bool
    {
        $this->order = Order::findByOrderIdInShop(
            $this->remembered_client->getShopId(),
            $this->remembered_client->getOrderId()
        );

        if (! $this->order) {
            $this->order = new Order([
                'shop_id'          => $this->remembered_client->getShopId(),
                'order_id_in_shop' => $this->remembered_client->getOrderId(),
                'order_price'      => $this->remembered_client->getOrderPrice(),
                'goods'            => $this->remembered_client->getGoods(),
                'status'           => 'pending_by_mfi',
            ]);

            if (! $this->order->create()) {
                $this->errors = $this->order->getErrors();

                return false;
            }
        }

        return true;
    }

    /**
     * Creates the request.
     *
     * @return bool
     * @throws \Exception
     */
    private function createRequest(): bool
    {
        $this->request = new Request([
            'client_id'            => $this->client->getId(),
            'shop_id'              => $this->remembered_client->getShopId(),
            'order_id'             => $this->order->getId(),
            'is_test_mode_enabled' => $this->remembered_client->getIsTestModeEnabled(),
            'is_loan_postponed'    => $this->remembered_client->getIsLoanPostponed(),
        ]);

        if (! $this->request->create()) {
            $this->errors = $this->request->getErrors();

            return false;
        }

        return true;
    }

    /**
     * Starts the crediting.
     *
     * @return void
     */
    private function startCrediting(): void
    {
        $path       = SiteInfo::getDocumentRoot() . '/exec/start-crediting.php';
        $request_id = $this->request->getId();

        Helper::execInBackground("$path $request_id");
    }

    /**
     * Starts the waiting for a limit.
     *
     * @return void
     */
    private function startWaitingForLimit(): void
    {
        $path       = SiteInfo::getDocumentRoot() . '/exec/start-waiting-for-limit.php';
        $request_id = $this->request->getId();

        Helper::execInBackground("$path $request_id");
    }

    /**
     * Gets the request max time.
     *
     * @return int
     */
    private function getRequestMaxTime(): int
    {
        return MFI::getRequestMaxTime(
            $this->remembered_client->getShopId(),
            $this->remembered_client->getOrderPrice(),
            $this->remembered_client->getIsLoanPostponed()
        );
    }

    /**
     * Gets the timer end.
     *
     * @return int
     */
    private function getTimerEnd(): int
    {
        return $this->request->getTimerEnd($this->getRequestMaxTime());
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
            $this->remembered_client->getCallbackUrl(),
            $shop->getIsOldIntegration()
        );

        return $callback_url;
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

    /**
     * Validates form.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     */
    private function validateForm(): bool
    {
        $validator = new Validator([
            'required'    => ':attribute — обязательное поле.',
            'required_if' => ':attribute — обязательное поле.',
            'numeric'     => ':attribute — поле должно содержать только цифры.',
            'regex'       => ':attribute — поле имеет некорректный формат.',
        ]);

        $validator->addValidator('plain', new PlainRule());
        $validator->addValidator('date_ru', new DateRule());

        $photo_rules = ($this->client ? '' : 'required|') . 'uploaded_file:0,10M,png,jpeg,jpg';

        // TODO figure out how to replace $_FILES with Symfony\Component\HttpFoundation.
        $validation = $validator->make($this->http_request->request->all() + $_FILES, [
            'last_name'                                   => 'required|plain',
            'first_name'                                  => 'required|plain',
            'middle_name'                                 => 'required|plain',
            'birth_date'                                  => 'required|date_ru',
            'birth_place'                                 => 'required|plain',
            'sex'                                         => 'required|plain',
            'is_last_name_changed'                        => 'default:0',
            'previous_last_name'                          => 'required_if:is_last_name_changed,1,yes,on|plain',
            'tin'                                         => 'required|numeric',
            'snils'                                       => 'required|regex:/^\d{3}-\d{3}-\d{3}\s\d{2}$/',
            'passport_number'                             => 'required|regex:/^\d{2}\s\d{2}\s\d{6}$/',
            'passport_division_code'                      => 'required|regex:/^\d{3}-\d{3}$/',
            'passport_issued_by'                          => 'required|plain',
            'passport_issued_date'                        => 'required|date_ru',
            'workplace'                                   => 'required|plain',
            'salary'                                      => 'required|regex:/^\d{1,6}$/',
            'reg_zip_code'                                => 'required|numeric',
            'reg_city'                                    => 'required|plain',
            'reg_street'                                  => 'plain',
            'reg_building'                                => 'required|plain',
            'reg_apartment'                               => 'plain',
            'is_address_matched'                          => 'default:0',
            'fact_zip_code'                               => 'required|numeric',
            'fact_city'                                   => 'required|plain',
            'fact_street'                                 => 'plain',
            'fact_building'                               => 'required|plain',
            'fact_apartment'                              => 'plain',
            'phone'                                       => 'required|regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
            'additional_phone'                            => 'regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
            'email'                                       => 'required|email',
            'photo_passport_main_spread'                  => $photo_rules,
            'photo_client_face_with_passport_main_spread' => $photo_rules,
        ]);

        $validation->setAliases([
            'last_name'                                   => 'Фамилия',
            'first_name'                                  => 'Имя',
            'middle_name'                                 => 'Отчество',
            'birth_date'                                  => 'Дата рождения',
            'birth_place'                                 => 'Место рождения',
            'sex'                                         => 'Пол',
            'is_last_name_changed'                        => 'Была изменена фамилия',
            'previous_last_name'                          => 'Предыдущая фамилия',
            'tin'                                         => 'ИНН',
            'snils'                                       => 'СНИЛС',
            'passport_number'                             => 'Серия и номер паспорта',
            'passport_division_code'                      => 'Код подразделения',
            'passport_issued_by'                          => 'Кем выдан паспорт',
            'passport_issued_date'                        => 'Дата выдачи паспорта',
            'workplace'                                   => 'Место работы',
            'salary'                                      => 'Ежемесячный доход',
            'reg_zip_code'                                => 'Почтовый индекс регистрации',
            'reg_city'                                    => 'Город регистрации',
            'reg_street'                                  => 'Улица регистрации',
            'reg_building'                                => 'Дом регистрации',
            'reg_apartment'                               => 'Квартира регистрации',
            'is_address_matched'                          => 'Совпадает с адресом регистрации',
            'fact_zip_code'                               => 'Почтовый индекс',
            'fact_city'                                   => 'Город',
            'fact_street'                                 => 'Улица',
            'fact_building'                               => 'Дом',
            'fact_apartment'                              => 'Квартира',
            'phone'                                       => 'Номер телефона',
            'additional_phone'                            => 'Дополнительный номер телефона',
            'email'                                       => 'Email',
            'photo_passport_main_spread'                  => 'Фотография главного разворота паспорта',
            'photo_client_face_with_passport_main_spread' =>
                'Фотография лица анфас рядом с главным разворотом паспорта',
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
    private function maybeSendAboutClientCreatedRequest(): void
    {
        if (Config::isDevServer()) {
            return;
        }

        $telegram_client_bot = new TelegramClientBot();

        /* @var Shop $shop */
        $shop = Shop::findById($this->remembered_client->getShopId());

        $client_name = $this->client->getLastName() . ' ' . $this->client->getFirstName() . ' '
            . $this->client->getMiddleName();

        $telegram_client_bot->clientCreatedRequest(
            $client_name,
            $this->remembered_client->getPhone(),
            $shop->getName(),
            $this->remembered_client->getOrderPrice(),
            $this->request->getId()
        );
    }

    /**
     * Sends the message by Telegram.
     *
     * @return void
     * @throws \Exception
     */
    private function maybeSendAboutClientWaitingForLimit(): void
    {
        if (Config::isDevServer()) {
            return;
        }

        $telegram_client_bot = new TelegramClientBot();

        /* @var Shop $shop */
        $shop = Shop::findById($this->remembered_client->getShopId());

        $client_name = $this->client->getLastName() . ' ' . $this->client->getFirstName() . ' '
            . $this->client->getMiddleName();

        $telegram_client_bot->clientWaitingForLimit(
            $client_name,
            $this->remembered_client->getPhone(),
            $shop->getName(),
            $this->remembered_client->getOrderPrice(),
            $this->request->getId()
        );
    }
}
