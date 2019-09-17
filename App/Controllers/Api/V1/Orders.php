<?php

namespace App\Controllers\Api\V1;

use App\Crediting;
use App\Email;
use App\Models\DeliveryService;
use App\Models\Order;
use App\Models\OrderCallback;
use App\Models\OrderToken;
use App\Models\Request;
use App\Models\Shop;
use App\PlainRule;
use App\SMS;
use Exception;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Orders.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\Api\V1
 */
class Orders extends Api
{
    /**
     * Creates the order.
     *
     * Methods: POST, GET
     * Paths: api/v1/orders, api/v1/orders/{id}
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function indexAction(): JsonResponse
    {
        try {
            if (! $this->checkMethods(['POST', 'GET'])) {
                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 405, ['Allow' => 'POST, GET']);
            }

            return $this->http_request->isMethod('POST') ? $this->maybeCreateOrder() : $this->maybeGetOrders();
        } catch (Exception $exception) {
            return $this->apiExceptionHandler($exception);
        }
    }

    /**
     * Confirms the order.
     *
     * Method - PATCH
     * Path - api/v1/orders/{id}/confirm
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function confirmAction(): JsonResponse
    {
        try {
            if (! $this->checkMethods(['PATCH'])) {
                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 405, ['Allow' => 'PATCH']);
            }

            if (! $shop_id = $this->maybeFindShopIdByToken()) {
                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 401);
            }

            $order_id = $this->getRouteParam('id');

            /* @var $order Order */
            $order = Order::findById($order_id);

            if (! $order) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'order-not-found',
                                'message' => 'Заказ с идентификатором ' . $order_id . ' не найден.',
                                'field'   => 'order_id'
                            ],
                        ],
                    ]
                ], 404);
            }

            if ($shop_id !== $order->getShopId()) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'order-not-belong-to-shop',
                                'message' => 'Заказ не принадлежит данному магазину.',
                                'field'   => 'order_id'
                            ],
                        ],
                    ]
                ], 403);
            }

            if (! in_array($order->getStatus(), ['pending_by_shop', 'waiting_for_delivery'])) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'order-сannot-be-changed',
                                'message' => 'Заказ имеет статус «' . $order->getStatusName()
                                    . '», поэтому его нельзя подтвердить или отклонить.',
                                'field'   => null,
                            ]
                        ],
                    ]
                ], 403);
            }

            /* @var $request Request */
            $request = Request::findByShopIdAndOrderId($shop_id, $order_id);

            if (! $request) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'request-not-found',
                                'message' => 'Заявка на кредит не найдена для данного заказа.',
                                'field'   => null,
                            ]
                        ],
                    ]
                ], 404);
            }

            $crediting = new Crediting($request->getId());

            if (! $crediting->confirmByShop(date('Y-m-d\TH:i:sP'))) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'internal-server-error',
                                'message' => 'Произошла ошибка попробуйте позже.',
                                'field'   => null,
                            ]
                        ],
                    ]
                ], 500);
            }

            return $this->sendJsonResponse(null, 204);
        } catch (Exception $exception) {
            return $this->apiExceptionHandler($exception);
        }
    }

    /**
     * Declines the order.
     *
     * Method - PATCH
     * Path - api/v1/orders/{order_id}/decline
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function declineAction(): JsonResponse
    {
        try {
            if (! $this->checkMethods(['PATCH'])) {
                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 405, ['Allow' => 'PATCH']);
            }

            if (! $shop_id = $this->maybeFindShopIdByToken()) {
                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 401);
            }

            $order_id = $this->getRouteParam('id');

            /* @var $order Order */
            $order = Order::findById($order_id);

            if (! $order) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'order-not-found',
                                'message' => 'Заказ с идентификатором ' . $order_id . ' не найден.',
                                'field'   => 'order_id'
                            ],
                        ],
                    ]
                ], 404);
            }

            if ($shop_id !== $order->getShopId()) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'order-not-belong-to-shop',
                                'message' => 'Заказ не принадлежит данному магазину.',
                                'field'   => 'order_id'
                            ],
                        ],
                    ]
                ], 403);
            }

            if (! in_array($order->getStatus(), ['pending_by_shop', 'waiting_for_delivery'])) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'order-сannot-be-changed',
                                'message' => 'Заказ имеет статус «' . $order->getStatusName()
                                    . '», поэтому его нельзя подтвердить или отклонить.',
                                'field'   => null,
                            ]
                        ],
                    ]
                ], 403);
            }

            /* @var $request Request */
            $request = Request::findByShopIdAndOrderId($shop_id, $order_id);

            if (! $request) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'request-not-found',
                                'message' => 'Заявка на кредит не найдена.',
                                'field'   => null,
                            ]
                        ],
                    ]
                ], 404);
            }

            $crediting = new Crediting($request->getId());

            if (! $crediting->declineByShop()) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'internal-server-error',
                                'message' => 'Произошла ошибка попробуйте позже.',
                                'field'   => null,
                            ]
                        ],
                    ]
                ], 500);
            }

            return $this->sendJsonResponse(null, 204);
        } catch (Exception $exception) {
            return $this->apiExceptionHandler($exception);
        }
    }

    /**
     * Deliver the order.
     *
     * Method - PATCH
     * Path - api/v1/orders/{order_id}/deliver
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function deliverAction(): JsonResponse
    {
        try {
            if (! $this->checkMethods(['PATCH'])) {
                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 405, ['Allow' => 'PATCH']);
            }

            if (! $this->validateDeliverRequest()) {
                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 400);
            }

            if (! $shop_id = $this->maybeFindShopIdByToken()) {
                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 401);
            }

            $order_id = $this->getRouteParam('id');

            /* @var $order Order */
            $order = Order::findById($order_id);

            if (! $order) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'order-not-found',
                                'message' => 'Заказ с идентификатором ' . $order_id . ' не найден.',
                                'field'   => 'order_id'
                            ],
                        ],
                    ]
                ], 404);
            }

            if ($shop_id !== $order->getShopId()) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'order-not-belong-to-shop',
                                'message' => 'Заказ не принадлежит данному магазину.',
                                'field'   => 'order_id'
                            ],
                        ],
                    ]
                ], 403);
            }

            if (! in_array($order->getStatus(), ['pending_by_shop', 'waiting_for_delivery'])) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'order-сannot-be-changed',
                                'message' => 'Заказ имеет статус «' . $order->getStatusName()
                                    . '», поэтому его нельзя подтвердить или отклонить.',
                                'field'   => null,
                            ]
                        ],
                    ]
                ], 403);
            }

            /* @var $request Request */
            $request = Request::findByShopIdAndOrderId($shop_id, $order_id);

            if (! $request) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'request-not-found',
                                'message' => 'Заявка на кредит не найдена.',
                                'field'   => null,
                            ]
                        ],
                    ]
                ], 404);
            }

            $delivery_service_id = $this->http_request->request->get('delivery_service_id');
            $tracking_code       = $this->http_request->request->get('tracking_code');

            if (! DeliveryService::getName($delivery_service_id)) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'delivery-service-not-found',
                                'message' => 'Служба доставки с идентификатором ' . $delivery_service_id
                                    . ' не найдена.',
                                'field'   => 'delivery_service_id',
                            ]
                        ],
                    ]
                ], 404);
            }

            $crediting = new Crediting($request->getId());

            if (! $crediting->deliverByShop($delivery_service_id, $tracking_code)) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'internal-server-error',
                                'message' => 'Произошла ошибка попробуйте позже.',
                                'field'   => null,
                            ]
                        ],
                    ]
                ], 500);
            }

            return $this->sendJsonResponse(null, 204);
        } catch (Exception $exception) {
            return $this->apiExceptionHandler($exception);
        }
    }

    /**
     * Maybe creates an order.
     *
     * @return JsonResponse
     * @throws Exception
     */
    private function maybeCreateOrder(): JsonResponse
    {
        if (! $this->validateCreateRequest()) {
            return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 400);
        }

        if (! $shop_id = $this->maybeFindShopIdByToken()) {
            return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 401);
        }

        $order_id_in_shop = $this->http_request->request->get('order_id_in_shop');
        $order_price      = $this->http_request->request->get('order_price');
        $goods            = $this->http_request->request->get('goods');
        $customer_phone   = $this->http_request->request->get('customer_phone');
        $customer_email   = $this->http_request->request->get('customer_email');
        $callback_url     = $this->http_request->request->get('callback_url');

        $order = new Order([
            'shop_id'          => $shop_id,
            'order_id_in_shop' => $order_id_in_shop,
            'order_price'      => $order_price,
            'goods'            => json_encode($goods),
            'status'           => 'waiting_for_registration',
        ]);

        if (! $order->create()) {
            foreach ($order->getErrors() as $error) {
                $this->errors[] = [
                    'code'    => 'entity-not-created',
                    'message' => $error,
                    'field'   => null,
                ];
            }

            return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 400);
        }

        if (! empty($callback_url)) {
            $order_callback = new OrderCallback([
                'order_id'     => $order->getId(),
                'callback_url' => $callback_url,
            ]);

            if (! $order_callback->create()) {
                foreach ($order_callback->getErrors() as $error) {
                    $this->errors[] = [
                        'code'    => 'entity-not-created',
                        'message' => $error,
                        'field'   => null,
                    ];
                }

                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 400);
            }
        }

        $order_token = new OrderToken([
            'order_id'       => $order->getId(),
            'customer_phone' => $customer_phone,
        ]);

        if (! $order_token->create()) {
            foreach ($order_token->getErrors() as $error) {
                $this->errors[] = [
                    'code'    => 'entity-not-created',
                    'message' => $error,
                    'field'   => null,
                ];
            }

            return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 400);
        }

        $this->sendOrderLink($shop_id, $order_token->getToken(), $customer_phone, $customer_email);

        return $this->sendJsonResponse(['data' => Order::getOrder($order->getId())], 201);
    }

    /**
     * Maybe gets orders.
     *
     * @return JsonResponse
     * @throws Exception
     */
    private function maybeGetOrders(): JsonResponse
    {
        if (! $shop_id = $this->maybeFindShopIdByToken()) {
            return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 401);
        }

        $order_id = $this->getRouteParam('id');

        if (! empty($order_id)) {
            /* @var $order Order */
            $order = Order::findById($order_id);

            if (! $order) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'order-not-found',
                                'message' => 'Заказ с идентификатором ' . $order_id . ' не найден.',
                                'field'   => 'order_id'
                            ],
                        ],
                    ]
                ], 404);
            }

            if ($shop_id !== $order->getShopId()) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'order-not-belong-to-shop',
                                'message' => 'Заказ не принадлежит данному магазину.',
                                'field'   => 'order_id'
                            ],
                        ],
                    ]
                ], 403);
            }

            return $this->sendJsonResponse(['data' => Order::getOrder($order_id)], 200);
        }

        if (! $this->validateGetOrdersRequest()) {
            return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 400);
        }

        $offset       = $this->http_request->query->getInt('offset', 0);
        $per_page     = $this->http_request->query->getInt('per_page', 10);
        $sort         = $this->http_request->query->get('sort', 'desc');
        $sort_by      = $this->http_request->query->get('sort_by', 'time_of_creation');
        $filter_by    = $this->http_request->query->get('filter_by', '');
        $filter_start = $this->http_request->query->get('filter_start', '');
        $filter_end   = $this->http_request->query->get('filter_end', '');
        $include      = $this->http_request->query->get('include', '');

        return $this->sendJsonResponse([
            'data' => [
                'items'      => Order::getOrders(
                    $shop_id,
                    0,
                    $offset,
                    $per_page,
                    $sort,
                    $sort_by,
                    $filter_by,
                    $filter_start,
                    $filter_end,
                    $include
                ),
                'statistics' => Order::getOrdersStatistics(
                    $shop_id,
                    0,
                    $filter_by,
                    $filter_start,
                    $filter_end
                ),
            ]
        ], 200);
    }

    /**
     * Sends the order link.
     *
     * @param int $shop_id The shop id.
     * @param string $token The token.
     * @param string|null $customer_phone The customer phone.
     * @param string|null $customer_email The customer email.
     *
     * @return void
     * @throws Exception
     */
    private function sendOrderLink(
        int $shop_id,
        string $token,
        string $customer_phone = null,
        string $customer_email = null
    ): void {
        /* @var $shop Shop */
        $shop = Shop::findById($shop_id);

        $shop_name = $shop->getName();

        if (! empty($customer_phone)) {
            do {
                $sms_send_result = SMS::sendOrderLink($customer_phone, $shop_name, $token);

                sleep(5);
            } while (! $sms_send_result);
        } elseif (! empty($customer_email)) {
            Email::sendOrderLink($customer_email, $shop_name, $token);
        }
    }

    /**
     * Validates the create order request.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     */
    private function validateCreateRequest(): bool
    {
        $validator = new Validator([
            'required'    => ':attribute — обязательное поле.',
            'required_if' => ':attribute — обязательное поле.',
            'numeric'     => ':attribute — поле должно содержать только цифры.',
            'array'       => ':attribute — поле должно содержать массив.',
            'regex'       => ':attribute — поле имеет некорректный формат.',
            'email'       => ':attribute — поле должно содержать корректный email.',
            'url'         => ':attribute — поле должно содержать корректный url.',
        ]);

        $validator->addValidator('plain', new PlainRule());

        $validation = $validator->make([
            'order_id_in_shop' => $this->http_request->request->get('order_id_in_shop'),
            'order_price'      => $this->http_request->request->get('order_price'),
            'goods'            => $this->http_request->request->get('goods'),
            'customer_phone'   => $this->http_request->request->get('customer_phone'),
            'customer_email'   => $this->http_request->request->get('customer_email'),
            'callback_url'     => $this->http_request->request->get('callback_url'),
        ], [
            'order_id_in_shop'      => 'required|plain',
            'order_price'           => 'required|numeric',
            'goods'                 => 'required|array',
            'goods.*'               => 'required|array',
            'goods.*.name'          => 'required|plain',
            'goods.*.price'         => 'required|numeric',
            'goods.*.quantity'      => 'required|numeric',
            'goods.*.is_returnable' => 'required|numeric',
            'customer_phone'        => 'regex:/^7\d{10}$/',
            'customer_email'        => 'required_if:customer_phone,null,|email',
            'callback_url'          => 'url',
        ]);

        $validation->setAliases([
            'order_id_in_shop'      => 'Идентификатор заказа',
            'order_price'           => 'Стоимость заказа',
            'goods'                 => 'Массив товаров',
            'goods.*.name'          => 'Название товара',
            'goods.*.price'         => 'Стоимость товара',
            'goods.*.quantity'      => 'Количество товара',
            'goods.*.is_returnable' => 'Возвратный ли товар',
            'customer_phone'        => 'Номер телефона покупателя',
            'customer_email'        => 'Email покупателя',
            'callback_url'          => 'Ссылка-коллбэк',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors()->toArray();

            foreach ($errors as $key => $error) {
                foreach ($error as $message) {
                    $this->errors[] = [
                        'code'    => 'invalid-field',
                        'message' => $message,
                        'field'   => $key,
                    ];
                }
            }

            return false;
        }

        return true;
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
            'offset'       => 'numeric',
            'per_page'     => 'numeric',
            'sort'         => 'plain',
            'sort_by'      => 'plain',
            'filter_by'    => 'plain',
            'filter_start' => 'plain',
            'filter_end'   => 'plain',
            'include'      => 'plain',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors()->toArray();

            foreach ($errors as $key => $error) {
                foreach ($error as $message) {
                    $this->errors[] = [
                        'code'    => 'invalid-field',
                        'message' => $message,
                        'field'   => $key,
                    ];
                }
            }

            return false;
        }

        return true;
    }

    /**
     * Validates the deliver order request.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     */
    private function validateDeliverRequest(): bool
    {
        $validator = new Validator([
            'required' => ':attribute — обязательное поле.',
            'numeric'  => ':attribute — поле должно содержать только цифры.',
        ]);

        $validator->addValidator('plain', new PlainRule());

        $validation = $validator->make([
            'delivery_service_id' => $this->http_request->request->get('delivery_service_id'),
            'tracking_code'       => $this->http_request->request->get('tracking_code'),
        ], [
            'delivery_service_id' => 'required|numeric',
            'tracking_code'       => 'required|plain',
        ]);

        $validation->setAliases([
            'delivery_service_id' => 'Идентификатор службы доставки',
            'tracking_code'       => 'Трекинг-код для отслеживания',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors()->toArray();

            foreach ($errors as $key => $error) {
                foreach ($error as $message) {
                    $this->errors[] = [
                        'code'    => 'invalid-field',
                        'message' => $message,
                        'field'   => $key,
                    ];
                }
            }

            return false;
        }

        return true;
    }
}
