<?php

namespace App;

/**
 * Class SiteInfo.
 *
 * @package App
 */
class SiteInfo
{
    /**
     * The site name.
     *
     * @var string
     */
    const NAME = 'Bliss';

    /**
     * The site phone number.
     *
     * @var string
     */
    const PHONE = '8(916)122-22-52';

    /**
     * The site second phone number.
     *
     * @var string
     */
    const SECOND_PHONE = '8(495)991-04-29';

    /**
     * The work time.
     *
     * @var string
     */
    const WORK_TIME = '09:00–19:00, пн–пт';

    /**
     * Gets the crediting email.
     *
     * @return string
     */
    public static function getCreditingEmail(): string
    {
        return Config::isDevServer() ? 'writer.noskov@yandex.ru' : 'support.cred@bliss24.ru';
    }

    /**
     * Gets the registering email.
     *
     * @return string
     */
    public static function getRegisteringEmail(): string
    {
        return Config::isDevServer() ? 'writer.noskov@yandex.ru' : 'kmishina@bliss24.ru';
    }

    /**
     * Gets the scheme and the http host.
     *
     * @codeCoverageIgnore
     *
     * @return string
     */
    public static function getSchemeAndHttpHost(): string
    {
        if (defined('DIESEL_SERVER')) {
            if (DIESEL_SERVER === 'local') {
                return 'http://bliss.local';
            } elseif (DIESEL_SERVER === 'dev') {
                return 'https://bliss.alef.im';
            }
        }

        return 'https://bliss24.ru';
    }

    /**
     * Gets the server document root.
     *
     * @return string
     */
    public static function getDocumentRoot(): string
    {
        return str_replace(['\\App', '/App'], '', __DIR__);
    }
}
