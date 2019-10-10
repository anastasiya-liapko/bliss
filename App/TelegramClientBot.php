<?php

namespace App;

use Exception;
use GuzzleHttp\HandlerStack;

/**
 * Class TelegramClientBot.
 *
 * @package App
 */
class TelegramClientBot
{
    /**
     * The Telegram dev token.
     *
     * @var string
     */
    const TELEGRAM_DEV_TOKEN = '661216265:AAEXilxn_51FaY4O7NJwq3MS_7MR-dLP0DI';

    /**
     * The Telegram dev chat id.
     *
     * @var string
     */
    const TELEGRAM_DEV_CHAT_ID = '-1001304189617';

    /**
     * The Telegram client token.
     *
     * @var string
     */
    const TELEGRAM_CLIENT_TOKEN = '808805378:AAGDgENX94T8FlSE2hlwQkK-_KNstp_oTkg';

    /**
     * The Telegram client chat id.
     *
     * @var string
     */
    const TELEGRAM_CLIENT_CHAT_ID = '-1001242017077';

    /**
     * The telegram bot handler.
     *
     * @var HandlerStack
     */
    private $handler_telegram_bot;

    /**
     * TelegramBotInformer constructor.
     *
     * @param HandlerStack $handler_telegram_bot (optional)
     */
    public function __construct(HandlerStack $handler_telegram_bot = null)
    {
        $this->handler_telegram_bot = $handler_telegram_bot;
    }

    /**
     * Sends a message about the client confirmed a phone number.
     *
     * @param string $phone
     * @param string $shop_name
     * @param float $order_price
     *
     * @return void
     * @throws Exception
     */
    public function clientConfirmedPhoneNumber(string $phone, string $shop_name, float $order_price): void
    {
        $message = '<b>Покупатель подтвердил номер телефона.</b>' . PHP_EOL;
        $message .= 'Номер телефона: ' . $phone . PHP_EOL;
        $message .= 'Магазин: ' . $shop_name . PHP_EOL;
        $message .= 'Сумма: ' . $order_price;

        $telegram = new Telegram(
            static::TELEGRAM_DEV_TOKEN,
            static::TELEGRAM_DEV_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);

        $telegram = new Telegram(
            static::TELEGRAM_CLIENT_TOKEN,
            static::TELEGRAM_CLIENT_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);
    }

    /**
     * Sends a message about the client create a request.
     *
     * @param string $client_name
     * @param string $phone
     * @param string $shop_name
     * @param float $order_price
     * @param int $request_id
     *
     * @return void
     * @throws Exception
     */
    public function clientCreatedRequest(
        string $client_name,
        string $phone,
        string $shop_name,
        float $order_price,
        int $request_id
    ): void {
        $message = '<b>Покупатель отправил заявку на рассмотрение.</b>' . PHP_EOL;
        $message .= 'Имя: ' . $client_name . PHP_EOL;
        $message .= 'Номер телефона: ' . $phone . PHP_EOL;
        $message .= 'Магазин: ' . $shop_name . PHP_EOL;
        $message .= 'Сумма: ' . $order_price . PHP_EOL;
        $message .= 'Идентификатор заявки: ' . $request_id;

        $telegram = new Telegram(
            static::TELEGRAM_DEV_TOKEN,
            static::TELEGRAM_DEV_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);

        $telegram = new Telegram(
            static::TELEGRAM_CLIENT_TOKEN,
            static::TELEGRAM_CLIENT_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);
    }

    /**
     * Sends a message about the client canceled a request.
     *
     * @param string $client_name
     * @param string $phone
     * @param string $shop_name
     * @param float $order_price
     * @param int $request_id
     *
     * @return void
     * @throws Exception
     */
    public function clientCanceledRequest(
        string $client_name,
        string $phone,
        string $shop_name,
        float $order_price,
        int $request_id
    ): void {
        $message = '<b>Покупатель отменил заявку.</b>' . PHP_EOL;
        $message .= 'Имя: ' . $client_name . PHP_EOL;
        $message .= 'Номер телефона: ' . $phone . PHP_EOL;
        $message .= 'Магазин: ' . $shop_name . PHP_EOL;
        $message .= 'Сумма: ' . $order_price . PHP_EOL;
        $message .= 'Идентификатор заявки: ' . $request_id;

        $telegram = new Telegram(
            static::TELEGRAM_DEV_TOKEN,
            static::TELEGRAM_DEV_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);

        $telegram = new Telegram(
            static::TELEGRAM_CLIENT_TOKEN,
            static::TELEGRAM_CLIENT_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);
    }

    /**
     * Sends a message about the client waiting for a limit.
     *
     * @param string $client_name
     * @param string $phone
     * @param string $shop_name
     * @param float $order_price
     * @param int $request_id
     *
     * @return void
     * @throws Exception
     */
    public function clientWaitingForLimit(
        string $client_name,
        string $phone,
        string $shop_name,
        float $order_price,
        int $request_id
    ): void {
        $message = '<b>Покупатель хочет дождаться выдачи лимита.</b>' . PHP_EOL;
        $message .= 'Имя: ' . $client_name . PHP_EOL;
        $message .= 'Номер телефона: ' . $phone . PHP_EOL;
        $message .= 'Магазин: ' . $shop_name . PHP_EOL;
        $message .= 'Сумма: ' . $order_price . PHP_EOL;
        $message .= 'Идентификатор заявки: ' . $request_id;

        $telegram = new Telegram(
            static::TELEGRAM_DEV_TOKEN,
            static::TELEGRAM_DEV_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);

        $telegram = new Telegram(
            static::TELEGRAM_CLIENT_TOKEN,
            static::TELEGRAM_CLIENT_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);
    }

    /**
     * Sends a message about the client confirmed a loan.
     *
     * @param string $client_name
     * @param string $phone
     * @param string $shop_name
     * @param float $order_price
     * @param int $request_id
     *
     * @return void
     * @throws Exception
     */
    public function clientConfirmedLoan(
        string $client_name,
        string $phone,
        string $shop_name,
        float $order_price,
        int $request_id
    ): void {
        $message = '<b>Покупатель подтвердил кредит.</b>' . PHP_EOL;
        $message .= 'Имя: ' . $client_name . PHP_EOL;
        $message .= 'Номер телефона: ' . $phone . PHP_EOL;
        $message .= 'Магазин: ' . $shop_name . PHP_EOL;
        $message .= 'Сумма: ' . $order_price . PHP_EOL;
        $message .= 'Идентификатор заявки: ' . $request_id;

        $telegram = new Telegram(
            static::TELEGRAM_DEV_TOKEN,
            static::TELEGRAM_DEV_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);

        $telegram = new Telegram(
            static::TELEGRAM_CLIENT_TOKEN,
            static::TELEGRAM_CLIENT_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);
    }

    /**
     * Sends a message about the client canceled a loan upon goods receipt.
     *
     * @param string $client_name
     * @param string $phone
     * @param string $shop_name
     * @param float $order_price
     * @param int $request_id
     *
     * @return void
     * @throws Exception
     */
    public function clientCanceledLoanUponReceipt(
        string $client_name,
        string $phone,
        string $shop_name,
        float $order_price,
        int $request_id
    ): void {
        $message = '<b>Покупатель отказался от заказа при получении товаров.</b>' . PHP_EOL;
        $message .= 'Имя: ' . $client_name . PHP_EOL;
        $message .= 'Номер телефона: ' . $phone . PHP_EOL;
        $message .= 'Магазин: ' . $shop_name . PHP_EOL;
        $message .= 'Сумма: ' . $order_price . PHP_EOL;
        $message .= 'Идентификатор заявки: ' . $request_id;

        $telegram = new Telegram(
            static::TELEGRAM_DEV_TOKEN,
            static::TELEGRAM_DEV_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);

        $telegram = new Telegram(
            static::TELEGRAM_CLIENT_TOKEN,
            static::TELEGRAM_CLIENT_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);
    }

    /**
     * Sends a message about the error.
     *
     * @param int $shop_id
     * @param array $errors
     *
     * @return void
     * @throws Exception
     */
    public function clientGetError(
        int $shop_id,
        array $errors
    ): void {
        $message = '<b>Покупатель получил ошибку(-и) при переходе на сайт.</b>' . PHP_EOL;
        $message .= 'Идентификатор магазина: ' . $shop_id . PHP_EOL;
        $message .= '<b>Ошибки</b>' . PHP_EOL;

        foreach ($errors as $error) {
            $message .= $error . PHP_EOL;
        }

        $telegram = new Telegram(
            static::TELEGRAM_DEV_TOKEN,
            static::TELEGRAM_DEV_CHAT_ID,
            $this->handler_telegram_bot
        );

        $telegram->sendMessage($message);
    }
}
