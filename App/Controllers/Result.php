<?php

namespace App\Controllers;

use App\Helper;
use App\Models\RememberedClient;
use App\SiteInfo;
use Core\Controller;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Result.
 *
 * @package App\Controllers
 */
class Result extends Controller
{
    /**
     * The remembered client model.
     *
     * @var RememberedClient
     */
    private $remembered_client;

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
        $status = $this->http_request->query->get('status');

        if (in_array($status, ['issued', 'issued_postponed'])) {
            $message = 'Оплата прошла успешно.';
        } elseif (in_array($status, ['declined', 'canceled', 'manual'])) {
            $message = 'Не удалось оплатить заказ.';
        } else {
            $message = 'Нет данных об оплате.';
        }

        return $this->render('Result/index.twig', [
            'title'               => 'Результаты оплаты заказа',
            'body_class'          => 'body_result',
            'phone_number'        => SiteInfo::PHONE,
            'phone_link'          => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number' => SiteInfo::SECOND_PHONE,
            'second_phone_link'   => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'           => SiteInfo::WORK_TIME,
            'message'             => $message,
        ]);
    }

    /**
     * Checks if the token is valid, phone is verified and status of request.
     *
     * @return bool
     * @throws Exception
     */
    protected function before(): bool
    {
        parent::before();

        if (! $this->remembered_client = $this->getRememberedClient($this->getRememberedClientToken())) {
            throw new Exception('No route matched.', 404);
        }

        if (! $this->remembered_client->getIsVerified() || $this->remembered_client->isTokenExpired()) {
            throw new Exception('No route matched.', 404);
        }

        return true;
    }

    /**
     * Gets the remembered client token.
     *
     * @return string
     */
    protected function getRememberedClientToken(): string
    {
        return $this->http_request->cookies->get('remembered_client', '');
    }

    /**
     * Gets the remembered client token.
     *
     * @param string $token The token.
     *
     * @return RememberedClient|false
     * @throws Exception
     */
    protected function getRememberedClient(string $token)
    {
        return RememberedClient::findByToken($token);
    }
}
