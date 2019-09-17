<?php

namespace App\Controllers\ShopAdminPanel;

use App\Models\Order;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class IssuedOrders.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\ShopAdminPanel
 */
class IssuedOrders extends ShopAdminPanel
{
    /**
     * Gets unpaid orders (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getUnpaidOrdersAction(): JsonResponse
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
                    7,
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
                    7,
                    $filter_by,
                    $filter_start,
                    $filter_end
                ),
            ],
        ]);
    }

    /**
     * Gets paid orders (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getPaidOrdersAction(): JsonResponse
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
                    8,
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
                    8,
                    $filter_by,
                    $filter_start,
                    $filter_end
                ),
            ],
        ]);
    }
}
