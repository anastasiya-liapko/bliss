<?php

namespace App\Controllers\Api\V1;

use App\Models\DeliveryService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DeliveryServices.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\Api\V1
 */
class DeliveryServices extends Api
{
    /**
     * Gets delivery services.
     *
     * Methods: GET
     * Path: api/v1/delivery-services
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function indexAction(): JsonResponse
    {
        try {
            if (! $this->checkMethods(['GET'])) {
                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 405, ['Allow' => 'GET']);
            }

            return $this->sendJsonResponse([
                'data' => [
                    'delivery_services' => DeliveryService::getAll(),
                ]
            ], 200);
        } catch (Exception $exception) {
            return $this->apiExceptionHandler($exception);
        }
    }
}
