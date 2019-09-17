<?php

namespace App\Controllers\ShopAdminPanel;

use App\Crediting;
use App\Models\DeliveryService;
use App\Models\Order;
use App\Models\OrderToken;
use App\Models\Request;
use App\PlainRule;
use App\SMS;
use Exception;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class IncomingOrders.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\ShopAdminPanel
 */
class IncomingOrders extends ShopAdminPanel
{
    /**
     * Gets delivery services (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getDeliveryServicesAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        return $this->sendJsonResponse([
            'data' => [
                'items' => DeliveryService::getAll(),
            ],
        ]);
    }

    /**
     * Gets pending orders (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getPendingOrdersAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateGetOrdersRequest()) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $offset       = $this->http_request->query->getInt('offset');
        $per_page     = $this->http_request->query->getInt('per_page');
        $sort         = $this->http_request->query->get('sort');
        $sort_by      = $this->http_request->query->get('sort_by') ?: 'time_of_creation';
        $filter_by    = $this->http_request->query->get('filter_by');
        $filter_start = $this->http_request->query->get('filter_start');
        $filter_end   = $this->http_request->query->get('filter_end');

        return $this->sendJsonResponse([
            'data' => [
                'items'      => Order::getOrders(
                    $this->shop->getId(),
                    4,
                    $offset,
                    $per_page,
                    $sort,
                    $sort_by,
                    $filter_by,
                    $filter_start,
                    $filter_end
                ),
                'statistics' => Order::getOrdersStatistics(
                    $this->shop->getId(),
                    4,
                    $filter_by,
                    $filter_start,
                    $filter_end
                ),
            ],
        ]);
    }

    /**
     * Gets potential orders (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getPotentialOrdersAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateGetOrdersRequest()) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $offset       = $this->http_request->query->getInt('offset');
        $per_page     = $this->http_request->query->getInt('per_page');
        $sort         = $this->http_request->query->get('sort');
        $sort_by      = $this->http_request->query->get('sort_by') ?: 'time_of_creation';
        $filter_by    = $this->http_request->query->get('filter_by');
        $filter_start = $this->http_request->query->get('filter_start');
        $filter_end   = $this->http_request->query->get('filter_end');

        return $this->sendJsonResponse([
            'data' => [
                'items'      => Order::getOrders(
                    $this->shop->getId(),
                    5,
                    $offset,
                    $per_page,
                    $sort,
                    $sort_by,
                    $filter_by,
                    $filter_start,
                    $filter_end
                ),
                'statistics' => Order::getOrdersStatistics(
                    $this->shop->getId(),
                    5,
                    $filter_by,
                    $filter_start,
                    $filter_end
                ),
            ],
        ]);
    }

    /**
     * Gets created orders (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getCreatedOrdersAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateGetOrdersRequest()) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $offset       = $this->http_request->query->getInt('offset');
        $per_page     = $this->http_request->query->getInt('per_page');
        $sort         = $this->http_request->query->get('sort');
        $sort_by      = $this->http_request->query->get('sort_by') ?: 'time_of_creation';
        $filter_by    = $this->http_request->query->get('filter_by');
        $filter_start = $this->http_request->query->get('filter_start');
        $filter_end   = $this->http_request->query->get('filter_end');

        return $this->sendJsonResponse([
            'data' => [
                'items'      => Order::getOrders(
                    $this->shop->getId(),
                    6,
                    $offset,
                    $per_page,
                    $sort,
                    $sort_by,
                    $filter_by,
                    $filter_start,
                    $filter_end
                ),
                'statistics' => Order::getOrdersStatistics(
                    $this->shop->getId(),
                    6,
                    $filter_by,
                    $filter_start,
                    $filter_end
                ),
            ],
        ]);
    }

    /**
     * Declines the order (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function declineAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateDeclineRequest()) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $order_id = $this->http_request->request->getInt('id');

        $this->checkOrder($order_id);

        /* @var Request $request */
        $request = Request::findByShopIdAndOrderId($this->shop->getId(), $order_id);

        $crediting = new Crediting($request->getId());

        return $this->sendJsonResponse([
            'data' => [
                'success' => $crediting->declineByShop(),
            ],
        ]);
    }

    /**
     * Deliver the order (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function deliverAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateDeliverRequest()) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $order_id            = $this->http_request->request->getInt('id');
        $delivery_service_id = $this->http_request->request->getInt('delivery_service_id');
        $tracking_code       = $this->http_request->request->get('tracking_code');

        $this->checkOrder($order_id);

        /* @var Request $request */
        $request = Request::findByShopIdAndOrderId($this->shop->getId(), $order_id);

        $crediting = new Crediting($request->getId());

        return $this->sendJsonResponse([
            'data' => [
                'success' => $crediting->deliverByShop($delivery_service_id, $tracking_code),
            ],
        ]);
    }

    /**
     * Issued the order (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function issueAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateIssueRequest()) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $order_id = $this->http_request->request->getInt('id');

        $this->checkOrder($order_id);

        /* @var Request $request */
        $request = Request::findByShopIdAndOrderId($this->shop->getId(), $order_id);

        $crediting = new Crediting($request->getId());

        return $this->sendJsonResponse([
            'data' => [
                'success' => $crediting->confirmByShop(date('Y-m-d\TH:i:sP')),
            ],
        ]);
    }

    /**
     * Create the order (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function createAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateCreateRequest()) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $phone       = $this->http_request->request->get('phone');
        $order_price = $this->http_request->request->get('order_price');
        $goods       = $this->http_request->request->get('goods');

        $order = new Order([
            'shop_id'          => $this->shop->getId(),
            'order_id_in_shop' => Order::getUniqueOrderIdInShop($this->shop->getId()),
            'order_price'      => $order_price,
            'goods'            => json_encode($goods),
            'status'           => 'waiting_for_registration',
        ]);

        if (! $order->create()) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $order_token = new OrderToken([
            'order_id'     => $order->getId(),
            'client_phone' => $phone,
        ]);

        if (! $order_token->create()) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $shop_name = $this->shop->getName();

        do {
            $sms_send_result = SMS::sendOrderLink($phone, $shop_name, $order_token->getToken());

            sleep(5);
        } while (! $sms_send_result);

        return $this->sendJsonResponse([
            'data' => [
                'success'            => true,
                'process_order_link' => $order_token->getProcessOrderLink(),
            ],
        ]);
    }

    /**
     * Validates the deliver request.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     */
    private function validateDeliverRequest(): bool
    {
        $validator = new Validator;

        $validator->addValidator('plain', new PlainRule());

        $validation = $validator->make($this->http_request->request->all(), [
            'id'                  => 'required|integer',
            'delivery_service_id' => 'required|integer',
            'tracking_code'       => 'required|plain',
        ]);

        $validation->validate();

        return ! $validation->fails();
    }

    /**
     * Validates the create request.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     */
    private function validateCreateRequest(): bool
    {
        $validator = new Validator;

        $validator->addValidator('plain', new PlainRule());

        $validation = $validator->make($this->http_request->request->all(), [
            'phone'                 => 'required|regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
            'order_price'           => 'required|numeric',
            'goods'                 => 'required|array',
            'goods.*'               => 'required|array',
            'goods.*.name'          => 'required|plain',
            'goods.*.price'         => 'required|numeric',
            'goods.*.quantity'      => 'required|numeric',
            'goods.*.is_returnable' => 'required|numeric',
        ]);

        $validation->validate();

        return ! $validation->fails();
    }
}
