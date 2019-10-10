<?php

namespace App\Controllers;

use App\Config;
use App\Helper;
use App\Models\Order;
use App\Models\Request;
use App\Models\Shop;
use App\SiteInfo;
use Core\Controller;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProcessOrder.
 *
 * @package App\Controllers
 */
class ProcessOrder extends Controller
{
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
        $token = $this->http_request->query->get('token');

        if (empty($token)) {
            throw new Exception('No route matched.', 404);
        }

        if (! $order = $this->getOrder($token)) {
            throw new Exception('No route matched.', 404);
        }

        $shop = $this->getShop($order->getShopId());

        $shop_id              = $order->getShopId();
        $order_id             = $order->getOrderIdInShop();
        $order_price          = $order->getOrderPrice();
        $goods                = $order->getGoods();
        $callback_url         = $this->getAbsUrl('/result');
        $is_loan_postponed    = 1;
        $is_test_mode_enabled = Config::isDevServer() ? 1 : 0;
        $secret_key           = $shop->getSecretKey();

        $signature = Request::createRequestSignature(
            $shop_id,
            $order_id,
            $order_price,
            $goods,
            $callback_url,
            $is_loan_postponed,
            $is_test_mode_enabled,
            $secret_key
        );

        return $this->render('ProcessOrder/index.twig', [
            'title'                => 'Обработка заказа',
            'body_class'           => 'body_process_order',
            'phone_number'         => SiteInfo::PHONE,
            'phone_link'           => Helper::getCleanPhone(SiteInfo::PHONE),
            'second_phone_number'  => SiteInfo::SECOND_PHONE,
            'second_phone_link'    => Helper::getCleanPhone(SiteInfo::SECOND_PHONE),
            'work_time'            => SiteInfo::WORK_TIME,
            'form_action'          => $this->getAbsUrl('/phone-number'),
            'message'              => 'Подождите, заказ обрабатывается...',
            'shop_id'              => $shop_id,
            'order_id'             => $order_id,
            'order_price'          => $order_price,
            'goods'                => $goods,
            'callback_url'         => $callback_url,
            'is_loan_postponed'    => $is_loan_postponed,
            'is_test_mode_enabled' => $is_test_mode_enabled,
            'signature'            => $signature,
        ]);
    }

    /**
     * Gets the order.
     *
     * @param string $token The token.
     *
     * @return Order|false
     * @throws Exception
     */
    protected function getOrder(string $token)
    {
        return Order::findByToken($token);
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
