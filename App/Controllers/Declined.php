<?php

namespace App\Controllers;

use App\Helper;
use App\Models\RememberedClient;
use App\Models\Request;
use App\Models\Shop;
use App\SiteInfo;
use Core\Controller;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Declined.
 *
 * @package App\Controllers
 */
class Declined extends Controller
{
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
     * Shows the index page.
     *
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws Exception
     */
    public function indexAction(): Response
    {
        $shop = $this->getShop($this->remembered_client->getShopId());

        return $this->render('Declined/index.twig', [
            'title'                              => 'Кредитные организации отказали',
            'body_class'                         => 'body_cancel',
            'phone_number'                       => SiteInfo::PHONE,
            'phone_link'                         => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number'                => SiteInfo::SECOND_PHONE,
            'second_phone_link'                  => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'                          => SiteInfo::WORK_TIME,
            'is_test_mode_enabled'               => $this->request->getIsTestModeEnabled(),
            'callback_url'                       => $this->getCallbackUrl(
                'declined',
                $this->remembered_client->getOrderId(),
                $shop->getSecretKey(),
                $this->remembered_client->getCallbackUrl(),
                $shop->getIsOldIntegration()
            ),
            'is_show_credit_history_information' => 0,
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

        $this->request = $this->getRequest(
            $this->remembered_client->getShopId(),
            $this->remembered_client->getOrderId()
        );

        if (! $this->request || $this->request->getStatus() !== 'declined') {
            $response = $this->redirect($this->getAbsUrl('/profile-client'));
            $response->send();

            return false;
        }

        return true;
    }

    /**
     * Gets the callback url.
     *
     * @param string $status The request status.
     * @param string $order_id_in_shop The order id in shop.
     * @param string $secret_key The shop secret key.
     * @param string $callback_url The callback url.
     * @param int $is_old_integration Is the old integration.
     *
     * @return string
     * @throws Exception
     */
    protected function getCallbackUrl(
        string $status,
        string $order_id_in_shop,
        string $secret_key,
        string $callback_url,
        int $is_old_integration
    ): string {
        return $this->request->getCallbackUrlWithParameters(
            $status,
            $order_id_in_shop,
            $secret_key,
            $callback_url,
            $is_old_integration
        );
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
     * Gets the remembered.
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

    /**
     * Gets the request.
     *
     * @param int $shop_id The shop id.
     * @param string $order_id_in_shop The order id in a shop.
     *
     * @return Request|false
     * @throws Exception
     */
    protected function getRequest(int $shop_id, string $order_id_in_shop)
    {
        return Request::findByOrderIdInShop($shop_id, $order_id_in_shop);
    }

    /**
     * Gets the shop.
     *
     * @param int $shop_id The shop id.
     *
     * @return Shop|false
     * @throws Exception
     */
    protected function getShop(int $shop_id)
    {
        return Shop::findById($shop_id);
    }
}
