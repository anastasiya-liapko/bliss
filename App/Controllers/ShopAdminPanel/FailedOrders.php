<?php

namespace App\Controllers\ShopAdminPanel;

use App\Models\Order;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class FailedOrders.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\ShopAdminPanel
 */
class FailedOrders extends ShopAdminPanel
{
    /**
     * Gets failed orders (Ajax).
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getFailedOrdersAction(): JsonResponse
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
                    3,
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
                    3,
                    $filter_by,
                    $filter_start,
                    $filter_end
                ),
            ],
        ]);
    }
}
