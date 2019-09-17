<?php

namespace App;

use Exception;
use GuzzleHttp\HandlerStack;

/**
 * Class TelegramOrganizationBot.
 *
 * @package App
 */
class TelegramOrganizationBot
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
    const TELEGRAM_CLIENT_TOKEN = '868653021:AAHY74K8MTBfLB-A179Bu6qWGGuO0wLisV4';

    /**
     * The Telegram client chat id.
     *
     * @var string
     */
    const TELEGRAM_CLIENT_CHAT_ID = '-1001422274521';

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
     * Sends a message about the organization created an account.
     *
     * @param string $organization_name
     *
     * @return void
     * @throws Exception
     */
    public function organizationCreatedAccount(string $organization_name): void
    {
        $message = 'Партнер ' . $organization_name . ' заполнил первую страницу формы регистрации.';

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
     * Sends a message about the organization download templates.
     *
     * @param string $organization_name
     *
     * @return void
     * @throws Exception
     */
    public function organizationDownloadTemplates(string $organization_name): void
    {
        $message = 'Партнер ' . $organization_name . ' скачал предзаполненные документы.';

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
     * Sends a message about the organization uploaded documents.
     *
     * @param string $organization_name
     *
     * @return void
     * @throws Exception
     */
    public function organizationUploadedDocuments(string $organization_name): void
    {
        $message = 'Партнер ' . $organization_name . ' загрузил документы.';

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
     * Sends a message about the mfi-shop cooperation.
     *
     * @param string $shop_name
     * @param string $mfi_name
     *
     * @return void
     * @throws Exception
     */
    public function addedMfiToShop(string $shop_name, string $mfi_name): void
    {
        $message = 'МФО ' . $mfi_name . ' теперь сотрудничает с магазином ' . $shop_name;

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
