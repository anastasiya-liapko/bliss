<?php

namespace App;

use Exception;
use GuzzleHttp\HandlerStack;

/**
 * Class TelegramMFIBot.
 *
 * @package App
 */
class TelegramMFIBot
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
     * Sends a message about the mfi gave response.
     *
     * @param string $mfi_name
     * @param int $request_id
     * @param string $status
     *
     * @return void
     * @throws Exception
     */
    public function mfiGaveResponse(string $mfi_name, int $request_id, string $status): void
    {
        $message = '<b>Ответ на заявку.</b>' . PHP_EOL;
        $message .= 'Название МФО: ' . $mfi_name . PHP_EOL;
        $message .= 'Идентификатор заявки: ' . $request_id . PHP_EOL;
        $message .= 'Статус: ' . $status;

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
     * Sends a message about did not have time for a request.
     *
     * @param int $request_id
     *
     * @return void
     * @throws Exception
     */
    public function didNotHaveTime(int $request_id): void
    {
        $message = '<b>Одна или несколько МФО не успели ответить на заявку.</b>' . PHP_EOL;
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
     * Sends a message about all mfi declined.
     *
     * @param int $request_id
     *
     * @return void
     * @throws Exception
     */
    public function allMfiDeclined(int $request_id): void
    {
        $message = '<b>Все МФО отказали.</b>' . PHP_EOL;
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
}
