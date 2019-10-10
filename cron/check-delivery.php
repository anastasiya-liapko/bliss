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

set_time_limit(0);

/**
 * Russian post.
 */
$orders = \App\Models\Order::getByDeliveryServiceSlug('russian_post');

if (! empty($orders)) {
    $russian_post = new \App\Logistics\RussianPost();

    foreach ($orders as $order) {
        $item_data = $russian_post->getItemStatus($order['tracking_code']);

        /* @var \App\Models\Request $request */
        $request   = \App\Models\Request::findByShopIdAndOrderId($order['shop_id'], $order['id']);
        $crediting = new \App\Crediting($request->getId());

        if (isset($item_data['status']) && $item_data['status'] === 'issued') {
            $crediting->confirmByShop($item_data['date'], $order['delivery_service_id'], $order['tracking_code']);
        } elseif (isset($item_data['status']) && $item_data['status'] === 'canceled_by_client_upon_receipt') {
            $crediting->cancelByClientUponReceipt();
        }

        sleep(1);
    }
}

die();
