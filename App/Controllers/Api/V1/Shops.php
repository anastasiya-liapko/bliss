<?php

namespace App\Controllers\Api\V1;

use App\Models\Shop;
use App\Models\ShopToken;
use App\PlainRule;
use Exception;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Shops.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\Api\V1
 */
class Shops extends Api
{
    /**
     * Gets the token.
     *
     * Method - GET.
     * Url - api/v1/shops/{shop_id}/token
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function tokenAction(): JsonResponse
    {
        try {
            if (! $this->checkMethods(['GET'])) {
                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 405, ['Allow' => 'GET']);
            }

            if (! $this->validateTokenRequest()) {
                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 400);
            }

            $shop_id = $this->getRouteParam('id');

            /* @var $shop Shop */
            $shop = Shop::findById($shop_id);

            if (! $shop) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'shop-not-found',
                                'message' => 'Магазин с идентификатором ' . $shop_id . ' не найден.',
                                'field'   => 'shop_id'
                            ],
                        ],
                    ]
                ], 404);
            }

            if ($shop->getSecretKey() !== $this->http_request->query->get('secret_key')) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'wrong-secret-key',
                                'message' => 'Секретный ключ не подходит.',
                                'field'   => 'secret_key'
                            ],
                        ],
                    ]
                ], 401);
            }

            if (! $shop->getIsActivated()) {
                return $this->sendJsonResponse([
                    'data' => [
                        'errors' => [
                            [
                                'code'    => 'shop-not-activated',
                                'message' => 'Магазин с идентификатором ' . $shop_id . ' не активирован.',
                                'field'   => 'shop_id'
                            ],
                        ],
                    ]
                ], 401);
            }

            $shop_token = new ShopToken(['shop_id' => $shop->getId()]);

            if (! $shop_token->create()) {
                foreach ($shop_token->getErrors() as $error) {
                    $this->errors[] = [
                        'code'    => 'entity-not-created',
                        'message' => $error,
                    ];
                }

                return $this->sendJsonResponse(['data' => ['errors' => $this->errors]], 400);
            }

            return $this->sendJsonResponse([
                'data' => [
                    'token'      => $shop_token->getToken(),
                    'expiration' => $shop_token->getTokenExpiresAt(),
                ]
            ], 200);
        } catch (Exception $exception) {
            return $this->apiExceptionHandler($exception);
        }
    }

    /**
     * Validates the token request.
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     */
    private function validateTokenRequest(): bool
    {
        $validator = new Validator([
            'required' => ':attribute — обязательное поле.',
            'numeric'  => ':attribute — поле должно содержать только цифры.',
        ]);

        $validator->addValidator('plain', new PlainRule());

        $validation = $validator->make([
            'shop_id'    => $this->getRouteParam('id'),
            'secret_key' => $this->http_request->query->get('secret_key'),
        ], [
            'shop_id'    => 'required|numeric',
            'secret_key' => 'required|plain',
        ]);

        $validation->setAliases([
            'shop_id'    => 'Идентификатор магазина',
            'secret_key' => 'Секретный ключ',
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
