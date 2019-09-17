<?php

namespace App\Controllers\ShopAdminPanel;

use App\Models\Order;
use App\Models\Shop;
use App\PlainRule;
use Core\Controller;
use Exception;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ShopAdminPanel.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\ShopAdminPanel
 */
abstract class ShopAdminPanel extends Controller
{
    /**
     * The shop.
     *
     * @var Shop
     */
    protected $shop;

    /**
     * Checks if exist the shop id in the session.
     *
     * @return void
     * @throws Exception
     */
    protected function before(): void
    {
        parent::before();

        // Here you can not use HttpFoundation, because the session was start through the admin panel
        // by session_start() function.
        if (isset($_SESSION['user']['shop_id']) && ! empty($_SESSION['user']['shop_id'])) {
            $this->shop = Shop::findById($_SESSION['user']['shop_id']);
        }

        if (empty($this->shop)) {
            throw new Exception('Forbidden.', 403);
        }
    }

    /**
     * Gets the order.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getOrderAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateGetOrderRequest()) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $order_id = $this->http_request->query->getInt('id');

        $this->checkOrder($order_id);

        return $this->sendJsonResponse([
            'data' => [
                'item' => Order::getOrder($order_id),
            ],
        ]);
    }

    /**
     * Checks if the order belong to a current shop.
     *
     * @param int $order_id The order id.
     *
     * @return void
     * @throws Exception
     */
    protected function checkOrder(int $order_id): void
    {
        if (! Order::isOrderBelongToShop($order_id, $this->shop->getId())) {
            throw new Exception('Forbidden.', 403);
        }
    }

    /**
     * Validates the get request.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     */
    protected function validateGetOrderRequest(): bool
    {
        $validator = new Validator;

        $validator->addValidator('plain', new PlainRule());

        $validation = $validator->make($this->http_request->query->all(), [
            'id' => 'present|integer',
        ]);

        $validation->validate();

        return ! $validation->fails();
    }

    /**
     * Validates the get orders request.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     */
    protected function validateGetOrdersRequest(): bool
    {
        $validator = new Validator;

        $validator->addValidator('plain', new PlainRule());

        $validation = $validator->make($this->http_request->query->all(), [
            'offset'       => 'present|integer',
            'per_page'     => 'present|integer',
            'sort'         => 'present|plain',
            'sort_by'      => 'present|plain',
            'filter_by'    => 'present|plain',
            'filter_start' => 'present|plain',
            'filter_end'   => 'present|plain',
            'include'      => 'plain',
        ]);

        $validation->validate();

        return ! $validation->fails();
    }

    /**
     * Validates the issue request.
     *
     * @return bool
     */
    protected function validateIssueRequest(): bool
    {
        $validator = new Validator;

        $validation = $validator->make($this->http_request->request->all(), [
            'id' => 'required|integer',
        ]);

        $validation->validate();

        return ! $validation->fails();
    }

    /**
     * Validates the decline request.
     *
     * @return bool
     */
    protected function validateDeclineRequest(): bool
    {
        $validator = new Validator;

        $validation = $validator->make($this->http_request->request->all(), [
            'id' => 'required|integer',
        ]);

        $validation->validate();

        return ! $validation->fails();
    }
}
