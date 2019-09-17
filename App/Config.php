<?php

namespace App;

/**
 * Class Config.
 *
 * @package App
 */
class Config
{
    /**
     * Secret key for hashing.
     *
     * @var string
     */
    const SECRET_KEY = 'TjWnZq4t7w!z%C*F-JaNdRgUkXp2s5u8';

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
     * Is the development server.
     *
     * @return bool
     */
    public static function isDevServer(): bool
    {
        return defined('DIESEL_SERVER') && in_array(DIESEL_SERVER, ['dev', 'local']);
    }

    /**
     * Is the local server.
     *
     * @return bool
     */
    public static function isLocalServer(): bool
    {
        return defined('DIESEL_SERVER') && DIESEL_SERVER === 'local';
    }
}
