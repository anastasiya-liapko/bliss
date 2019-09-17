<?php

namespace App;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

/**
 * Class SMSRu.
 *
 * @package App
 */
class SMSRu
{
    /**
     * The api url.
     *
     * @var string
     */
    const API_URL = 'https://sms.ru/sms/';

    /**
     * Api id.
     *
     * @var string
     */
    private $api_id;

    /**
     * The handler.
     *
     * @var HandlerStack
     */
    private $handler;

    /**
     * The telegram bot handler.
     *
     * @var HandlerStack
     */
    private $handler_telegram_bot;

    /**
     * HTTP Client.
     *
     * @var Client
     */
    private $http_client;

    /**
     * SMSRu constructor.
     *
     * @param HandlerStack $handler (optional)
     * @param HandlerStack $handler_telegram_bot (optional)
     *
     * @throws Exception
     */
    public function __construct($handler = null, $handler_telegram_bot = null)
    {
        $this->api_id = Config::isLocalServer()
            ? 'AF481CC8-7517-8F7D-C61B-719AFE68BFA2' : '869A16E7-9004-FD91-FFB2-74E275A408E0';

        $file_name     = 'sms.ru/' . date('Y-m-d') . '.log';
        $this->handler = $handler ?? Logging::createLoggingHandlerStack('sms.ru', $file_name);

        $this->handler_telegram_bot = $handler_telegram_bot;

        $this->http_client = new Client([
            'base_uri' => static::API_URL,
            'headers'  => [
                'Content-Type' => 'application/json',
            ],
            'handler'  => $this->handler,
        ]);
    }

    /**
     * Sends sms.
     *
     * @param string $to The receiver.
     * @param string $text The text.
     * @param int $is_test_mode Optional. Is test mode enabled.
     *
     * @return bool
     * @throws Exception
     */
    public function send(string $to, string $text, int $is_test_mode = 0): bool
    {
        try {
            $response = $this->http_client->get('send', [
                'query' => [
                    'api_id' => $this->api_id,
                    'to'     => $to,
                    'msg'    => $text,
                    'json'   => 1,
                    'test'   => $is_test_mode,
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents());

                if ($result->status === 'OK') {
                    return true;
                }

                $this->sendAlarmByTelegramBot($result->status_code);
            }
        } catch (Exception $e) {
            // I'm busy doing nothing.
        }

        return false;
    }

    /**
     * Sends the alarm by the Telegram bot.
     *
     * @param int $code The code.
     *
     * @return void
     * @throws Exception
     */
    private function sendAlarmByTelegramBot(int $code): void
    {
        switch ($code) {
            case 200:
                $message = '<b>SMS.RU can\'t send SMS</b>' . PHP_EOL;
                $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
                $message .= 'Cause: incorrect api_id' . PHP_EOL;
                break;
            case 201:
                $message = '<b>SMS.RU can\'t send SMS</b>' . PHP_EOL;
                $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
                $message .= 'Cause: not enough funds on the personal account' . PHP_EOL;
                break;
            case 206:
                $message = '<b>SMS.RU can\'t send SMS</b>' . PHP_EOL;
                $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
                $message .= 'Cause: the daily limit for sending messages will be exceeded or has already been exceeded'
                    . PHP_EOL;
                break;
            case 220:
                $message = '<b>SMS.RU can\'t send SMS</b>' . PHP_EOL;
                $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
                $message .= 'Cause: the service is temporarily unavailable, please try again later' . PHP_EOL;
                break;
            case 230:
                $message = '<b>SMS.RU can\'t send SMS</b>' . PHP_EOL;
                $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
                $message .= 'Cause: exceeded the total limit of the number of messages on this number per day'
                    . PHP_EOL;
                break;
            case 231:
                $message = '<b>SMS.RU can\'t send SMS</b>' . PHP_EOL;
                $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
                $message .= 'Cause: limit of identical messages per minute exceeded' . PHP_EOL;
                break;
            case 232:
                $message = '<b>SMS.RU can\'t send SMS</b>' . PHP_EOL;
                $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
                $message .= 'Cause: exceeded the limit of identical messages to this number on the day' . PHP_EOL;
                break;
            case 300:
                $message = '<b>SMS.RU can\'t send SMS</b>' . PHP_EOL;
                $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
                $message .= 'Cause: wrong token (may have expired or your IP changed)' . PHP_EOL;
                break;
            case 500:
                $message = '<b>SMS.RU can\'t send SMS</b>' . PHP_EOL;
                $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
                $message .= 'Cause: error on server. Retry the request' . PHP_EOL;
                break;
        }

        if (isset($message)) {
            $telegram = new Telegram(
                Config::TELEGRAM_DEV_TOKEN,
                Config::TELEGRAM_DEV_CHAT_ID,
                $this->handler_telegram_bot
            );
            $telegram->sendMessage($message);
        }
    }
}
