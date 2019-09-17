<?php

namespace App\Controllers\Api\V1;

use App\Config;
use App\Models\ShopToken;
use Core\Controller;
use Core\Error;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Api.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\Api\V1
 */
abstract class Api extends Controller
{
    /**
     * Errors.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Maybe finds the shop id by the token.
     *
     * @return int|null
     * @throws Exception
     */
    protected function maybeFindShopIdByToken(): ?int
    {
        if (! $this->http_request->headers->has('Authorization')) {
            $this->errors[] = [
                'code'    => 'authentication-token-not-passed',
                'message' => 'Отсутствует заголовок Authorization.',
                'field'   => 'token',
            ];

            return null;
        }

        if (! $bearer_token = $this->getBearerToken($this->http_request->headers->get('Authorization'))) {
            $this->errors[] = [
                'code'    => 'invalid-authentication-token',
                'message' => 'Неверно передан заголовок Authorization.',
                'field'   => 'token',
            ];

            return null;
        }

        /* @var $shop_token ShopToken */
        if (! $shop_token = ShopToken::findByToken($bearer_token)) {
            $this->errors[] = [
                'code'    => 'authentication-token-does-not-exist',
                'message' => 'Токен не существует.',
                'field'   => 'token',
            ];

            return null;
        }

        if ($shop_token->isTokenExpired()) {
            $this->errors[] = [
                'code'    => 'authentication-token-expired',
                'message' => 'Токен просрочен.',
                'field'   => 'token',
            ];

            return null;
        }

        return $shop_token->getShopId();
    }

    /**
     * Checks the methods.
     *
     * @param array $accepted_methods The accepted methods.
     *
     * @return bool
     */
    protected function checkMethods(array $accepted_methods): bool
    {
        if (! in_array($this->http_request->getMethod(), $accepted_methods)) {
            $this->errors[] = [
                'code'    => 'method-not-allowed',
                'message' => 'Метод не поддерживается для данного ресурса. Используйте: '
                    . join(', ', $accepted_methods),
                'field'   => null,
            ];

            return false;
        }

        return true;
    }

    /**
     * Gets the bearer token.
     *
     * @param string $authorization_header The authorization header.
     *
     * @return string|null
     */
    private function getBearerToken(string $authorization_header): ?string
    {
        if (! empty($authorization_header)) {
            if (preg_match('/Bearer\s(\S+)/', $authorization_header, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Api exception handler.
     *
     * @param Exception $exception
     *
     * @return JsonResponse
     * @throws Exception
     *
     */
    protected function apiExceptionHandler(Exception $exception): JsonResponse
    {
        Error::logException($exception, 500);

        if (! Config::isLocalServer()) {
            Error::sendException($exception);
        }

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
}
