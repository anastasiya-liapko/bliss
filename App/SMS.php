<?php

namespace App;

use GuzzleHttp\HandlerStack;

/**
 * Class SMS.
 *
 * @package App\Models
 */
class SMS
{
    /**
     * Sends the sms with a link on the order.
     *
     * @param string $phone The phone.
     * @param string $shop_name The shop name.
     * @param string $order_token The order token.
     * @param HandlerStack $handler (optional) The handler.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public static function sendOrderLink(
        string $phone,
        string $shop_name,
        string $order_token,
        $handler = null
    ): bool {
        $link = SiteInfo::getSchemeAndHttpHost() . '/process-order?token=' . $order_token;

        $text = "Для оформления заказа из магазина {$shop_name} "
            . "перейдите по ссылке $link";

        return static::send($phone, $text, $handler);
    }

    /**
     * Sends the sms with a link.
     *
     * @param string $phone The phone.
     * @param string $remembered_client_token The request token.
     * @param HandlerStack $handler (optional) The handler.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public static function sendRememberedClientLink(
        string $phone,
        string $remembered_client_token,
        $handler = null
    ): bool {
        $link = SiteInfo::getSchemeAndHttpHost() . '/process-remembered-client?token=' . $remembered_client_token;

        $text = "Извините за длительное ожидание. Вам был одобрен кредит. Пожалуйста, для подтверждения перейдите по "
            . "ссылке {$link}";

        return static::send($phone, $text, $handler);
    }

    /**
     * Sends the sms.
     *
     * @param string $phone Phone number.
     * @param string $text Text.
     * @param HandlerStack $handler (optional) The handler.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public static function send(string $phone, string $text, $handler = null): bool
    {
        $is_test_mode = defined('DIESEL_SERVER') && DIESEL_SERVER === 'local' ? 1 : 0;

        $sms_ru = new SMSRu($handler);

        return $sms_ru->send($phone, $text, $is_test_mode);
    }
}
