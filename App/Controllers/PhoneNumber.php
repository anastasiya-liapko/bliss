<?php

namespace App\Controllers;

use App\Config;
use App\Helper;
use App\Models\LockedPhone;
use App\Models\RememberedClient;
use App\PlainRule;
use App\SiteInfo;
use App\SMS;
use Core\Controller;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PhoneNumber.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers
 */
class PhoneNumber extends Controller
{
    /**
     * Errors.
     *
     * @var array
     */
    private $errors;

    /**
     * Shows index page.
     *
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function indexAction(): Response
    {
        $this->maybeSaveHttpRefererInSession();

        $this->logUserRequest();

        if (! $this->validateRequest()) {
            foreach ($this->errors as $error) {
                $this->session->getFlashBag()->add('error', $error);
            }

            return $this->redirect($this->getAbsUrl('/error'));
        }

        if (! Config::isDevServer() && $this->http_request->request->getInt('is_test_mode_enabled') === 1) {
            $this->session->getFlashBag()->add('error', 'Используйте тестовый сервер для тестовых заказов.');

            return $this->redirect($this->getAbsUrl('/error'));
        }

        $remembered_client = new RememberedClient([
            'shop_id'              => $this->http_request->request->getInt('shop_id'),
            'order_id'             => $this->http_request->request->get('order_id'),
            'order_price'          => $this->http_request->request->get('order_price'),
            'callback_url'         => $this->http_request->request->get('callback_url'),
            'is_loan_postponed'    => $this->http_request->request->getInt('is_loan_postponed'),
            'goods'                => $this->http_request->request->get('goods'),
            'is_test_mode_enabled' => $this->http_request->request->getInt('is_test_mode_enabled'),
            'signature'            => $this->http_request->request->get('signature'),
        ]);

        if (! $remembered_client->create()) {
            foreach ($remembered_client->getErrors() as $error) {
                $this->session->getFlashBag()->add('error', $error);
            }

            return $this->redirect($this->getAbsUrl('/error'));
        }

        $response = new Response();
        $response->headers->clearCookie('confirm_timer');
        $response->headers->setCookie(Cookie::create(
            'remembered_client',
            $remembered_client->getToken(),
            $remembered_client->getTokenExpiresAt(),
            '/'
        ));

        return $this->render('PhoneNumber/index.twig', [
            'title'                => 'Подтверждение телефона',
            'body_class'           => 'body_phone-number',
            'phone_number'         => SiteInfo::PHONE,
            'phone_link'           => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number'  => SiteInfo::SECOND_PHONE,
            'second_phone_link'    => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'            => SiteInfo::WORK_TIME,
            'form_action'          => $this->getAbsUrl('/phone-number/get-code'),
            'is_test_mode_enabled' => $remembered_client->getIsTestModeEnabled(),
            'shop_back_url'        => $this->session->get('shop_back_url'),
        ], $response);
    }

    /**
     * Saves the phone and sends a sms-code (Ajax).
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getCodeAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        /* @var RememberedClient $remembered_client */
        $remembered_client = RememberedClient::findByToken($this->getRememberedClientToken());

        if (! $remembered_client) {
            $this->session->getFlashBag()->add(
                'error',
                'Ваш токен не найден.'
            );

            return $this->redirect($this->getAbsUrl('/error'));
        }

        if (! $this->validateForm()) {
            return $this->sendJsonResponse(['errors' => $this->errors]);
        }

        if (LockedPhone::isLocked($this->http_request->request->get('phone'))) {
            $this->session->getFlashBag()->add(
                'error',
                'Вы 10 раз ввели неверный код. Ваш номер заблокирован на сутки.'
            );

            return $this->redirect($this->getAbsUrl('/error'));
        }

        if (! $remembered_client->savePhone($this->http_request->request->get('phone'))) {
            return $this->sendJsonResponse(['errors' => $remembered_client->getErrors()]);
        }

        if (! SMS::send($remembered_client->getPhone(), $remembered_client->getSmsCode())) {
            return $this->sendJsonResponse([
                'message' => ['body' => 'Не удалось отправить код, попробуйте ещё раз.', 'type' => 'error'],
            ]);
        }

        return $this->redirect($this->getAbsUrl('/code-sms'));
    }

    /**
     * Maybe saves http referer url in the session.
     *
     * @return void
     */
    private function maybeSaveHttpRefererInSession(): void
    {
        if ($this->http_request->server->has('HTTP_REFERER')) {
            $this->session->set('shop_back_url', $this->http_request->server->get('HTTP_REFERER'));
        }
    }

    /**
     * Logs the user request.
     *
     * @return void
     * @throws \Exception
     */
    private function logUserRequest(): void
    {
        $date_format = 'd-m-Y H:m:i';

        $output = "[%datetime%] %channel%.%level_name%: <<<<<<<< POST %message% %context% %extra%\n\n";

        $formatter = new LineFormatter($output, $date_format, true, true);

        $log_requests_dir = SiteInfo::getDocumentRoot() . '/logs/requests/';
        $log_file         = $log_requests_dir . date('Y-m-d') . '.log';

        $stream = new StreamHandler($log_file, Logger::INFO);
        $stream->setFormatter($formatter);

        $logger = new Logger('requests');
        $logger->pushHandler($stream);

        $logger->info(
            $this->http_request->getPathInfo() . ' '
            . $this->http_request->getProtocolVersion() . ' '
            . 'Content-Length: ' . $this->http_request->headers->get('Content-Length') . ' '
            . 'User-Agent: ' . $this->http_request->headers->get('User-Agent') . ' '
            . 'Host: ' . $this->http_request->getHost() . ' '
            . 'Content-Type: ' . $this->http_request->headers->get('Content-Type') . ' '
            . json_encode($this->http_request->request->all(), JSON_UNESCAPED_UNICODE)
        );
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
     * Validates the request.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     */
    private function validateRequest(): bool
    {
        $validator = new Validator([
            'required' => ':attribute — обязательное поле.',
            'numeric'  => ':attribute - поле должно содержать только цифры.',
        ]);

        $validator->addValidator('plain', new PlainRule());

        $validation = $validator->make($this->http_request->request->all(), [
            'shop_id'              => 'required|numeric',
            'order_id'             => 'required|plain',
            'order_price'          => 'required|numeric',
            'callback_url'         => 'required|plain',
            'is_loan_postponed'    => 'required|numeric',
            'goods'                => 'required|plain',
            'is_test_mode_enabled' => 'required|numeric',
            'signature'            => 'required|plain',
        ]);

        $validation->setAliases([
            'shop_id'              => 'Идентификатор магазина',
            'order_id'             => 'Идентификатор заказа',
            'order_price'          => 'Стоимость заказа',
            'callback_url'         => 'Ссылка на магазин',
            'is_loan_postponed'    => 'Отложенный ли кредит',
            'goods'                => 'Массив товаров',
            'is_test_mode_enabled' => 'Тестовая ли заявка',
            'signature'            => 'Подпись',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->errors = $validation->errors()->all();

            return false;
        }

        return true;
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
            'phone' => 'required|regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
        ]);

        $validation->setAliases([
            'phone' => 'Номер телефона',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->errors = $validation->errors()->all();

            return false;
        }

        return true;
    }
}
