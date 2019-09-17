<?php

/**
 * Composer.
 */
require(__DIR__ . '/../vendor/autoload.php');

/**
 * Error and Exception handling.
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Session.
 */
$session = new Symfony\Component\HttpFoundation\Session\Session();
$session->start();

/**
 * Http request.
 */
$http_request = Symfony\Component\HttpFoundation\Request::createFromGlobals();

Symfony\Component\HttpFoundation\Request::setTrustedProxies(
    [$http_request->server->get('REMOTE_ADDR')],
    Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_ALL
);

if ($http_request->getContentType() === 'json'
    && in_array($http_request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])
) {
    $json = json_decode($http_request->getContent(), true);

    if (is_array($json)) {
        $http_request->request = new Symfony\Component\HttpFoundation\ParameterBag($json);
    }
}

/**
 * Routing.
 */
$router = new Core\Router();
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('profile-client', ['controller' => 'ProfileClient', 'action' => 'index']);
$router->add('profile-shop', ['controller' => 'ProfileShop', 'action' => 'index']);
$router->add('declined', ['controller' => 'Declined', 'action' => 'index']);
$router->add('code-sms', ['controller' => 'CodeSms', 'action' => 'index']);
$router->add('success', ['controller' => 'Success', 'action' => 'index']);
$router->add('error', ['controller' => 'Error', 'action' => 'index']);
$router->add('phone-number', ['controller' => 'PhoneNumber', 'action' => 'index']);
$router->add('process-order', ['controller' => 'ProcessOrder', 'action' => 'index']);
$router->add('process-remembered-client', ['controller' => 'ProcessRememberedClient', 'action' => 'index']);
$router->add('result', ['controller' => 'Result', 'action' => 'index']);
$router->add('test', ['controller' => 'Test', 'action' => 'index']);
$router->add('{controller}/{action}');

$router->add('admin-panel/{controller}/{action}', ['namespace' => 'AdminPanel']);

$router->add('shop-admin-panel/{controller}/{action}', ['namespace' => 'ShopAdminPanel']);

$router->add('api/v1/delivery-services', [
    'namespace'  => 'Api\\V1',
    'controller' => 'DeliveryServices',
    'action'     => 'index'
]);
$router->add('api/v1/orders', ['namespace' => 'Api\\V1', 'controller' => 'Orders', 'action' => 'index']);
$router->add('api/v1/orders/{id:\d+}', ['namespace' => 'Api\\V1', 'controller' => 'Orders', 'action' => 'index']);
$router->add('api/v1/{controller}/{id:\d+}/{action}', ['namespace' => 'Api\\V1']);

$router->dispatch($http_request->server->get('QUERY_STRING'));
