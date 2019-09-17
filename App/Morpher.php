<?php

namespace App;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;

/**
 * Class Morpher.
 *
 * @package App
 */
class Morpher
{
    /**
     * The api url.
     *
     * @var string
     */
    const API_URL = 'https://ws3.morpher.ru';

    /**
     * The token.
     *
     * @var string
     */
    const TOKEN = 'e651efba-ccda-4176-87fa-85388aff93e6';

    /**
     * The handler.
     *
     * @var HandlerStack
     */
    private $handler;

    /**
     * HTTP Client.
     *
     * @var Client
     */
    private $http_client;

    /**
     * The telegram bot handler.
     *
     * @var HandlerStack
     */
    private $handler_telegram_bot;

    /**
     * TelegramBot constructor.
     *
     * @param HandlerStack $handler (optional). HandlerStack object.
     * @param HandlerStack $handler_telegram_bot (optional) HandlerStack object.
     *
     * @throws Exception
     */
    public function __construct($handler = null, $handler_telegram_bot = null)
    {
        $file_name                  = 'morpher/' . date('Y-m-d') . '.log';
        $this->handler              = $handler ?? Logging::createLoggingHandlerStack('Morpher', $file_name);
        $this->handler_telegram_bot = $handler_telegram_bot;

        $this->http_client = new Client([
            'base_uri' => static::API_URL,
            'handler'  => $this->handler,
        ]);
    }

    /**
     * Gets the inclined name.
     *
     * @param string $word The word.
     * @param bool $is_name Is word the name.
     *
     * @return mixed
     * @throws Exception
     */
    public function getInclinedWord(string $word, bool $is_name = false)
    {
        $url = '/russian/declension?s=' . rawurlencode($word) . '&format=json';

        if ($is_name) {
            $url .= '&flags=name';
        }

        try {
            $response = $this->http_client->get($url, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode(static::TOKEN),
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents());

                return $result;
            }
        } catch (Exception $exception) {
            $this->sendAlarmByTelegramBot($exception);
        }

        return false;
    }

    /**
     * Sends the warning by the Telegram bot.
     *
     * @param Exception $exception
     *
     * @return void
     * @throws Exception
     */
    private function sendAlarmByTelegramBot(Exception $exception): void
    {
        if ($exception instanceof RequestException && $exception->hasResponse()) {
            $stream = $exception->getResponse()->getBody();
            $stream->rewind();
            $result      = json_decode($stream->getContents());
            $status_code = $exception->getResponse()->getStatusCode();

            if (! in_array($status_code, [495, 496])) {
                $message = '<b>morpher.ru can\'t process request</b>' . PHP_EOL;
                $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
                $message .= 'Status code: ' . $exception->getResponse()->getStatusCode() . PHP_EOL;
                $message .= 'Reason phrase: ' . $exception->getResponse()->getReasonPhrase() . PHP_EOL;
                $message .= 'Error message: ' . $result->message . PHP_EOL;

                $telegram = new Telegram(
                    Config::TELEGRAM_DEV_TOKEN,
                    Config::TELEGRAM_DEV_CHAT_ID,
                    $this->handler_telegram_bot
                );
                $telegram->sendMessage($message);
            }
        }
    }
}
