<?php

namespace App\Controllers\ShopAdminPanel;

use App\Crediting;
use App\Models\Order;
use App\Models\Request;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DeliveringOrders.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\ShopAdminPanel
 */
class DeliveringOrders extends ShopAdminPanel
{
    /**
     * Gets manual delivering orders (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getManualDeliveringOrdersAction(): JsonResponse
    {
        $this->forbidNotXmlHttpRequest();

        if (! $this->validateGetOrdersRequest()) {
            return $this->sendJsonResponse(['data' => ['success' => false]]);
        }

        $offset       = $this->http_request->query->getInt('offset');
        $per_page     = $this->http_request->query->getInt('per_page');
        $sort         = $this->http_request->query->get('sort', 'desc');
        $sort_by      = $this->http_request->query->get('sort_by') ?: 'time_of_creation';
        $filter_by    = $this->http_request->query->get('filter_by');
        $filter_start = $this->http_request->query->get('filter_start');
        $filter_end   = $this->http_request->query->get('filter_end');

        return $this->sendJsonResponse([
            'data' => [
                'items'      => Order::getOrders(
                    $this->shop->getId(),
                    2,
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
                    2,
                    $filter_by,
                    $filter_start,
                    $filter_end
                ),
            ],
        ]);
    }

    /**
     * Gets auto delivering orders (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getAutoDeliveringOrdersAction(): JsonResponse
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
                    1,
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
                    1,
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
}
