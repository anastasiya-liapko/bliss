<?php

namespace App;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;

/**
 * Class Dadata.
 *
 * @package App
 */
class Dadata
{
    /**
     * The api url.
     *
     * @var string
     */
    const API_URL = 'https://dadata.ru/api/v2/';

    /**
     * The token.
     *
     * @var string
     */
    const TOKEN = '9ebe20528635d393d6373433ab86d50d2e60c507';

    /**
     * The secret.
     *
     * @var string
     */
    const SECRET = 'b058142d53efe77b5c43f41ffd2381ea946a16fa';

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
     * Dadata constructor.
     *
     * @param HandlerStack $handler (optional). HandlerStack object.
     * @param HandlerStack $handler_telegram_bot (optional) HandlerStack object.
     *
     * @throws Exception
     */
    public function __construct($handler = null, $handler_telegram_bot = null)
    {
        $file_name                  = 'dadata/' . date('Y-m-d') . '.log';
        $this->handler              = $handler ?? Logging::createLoggingHandlerStack('Dadata', $file_name);
        $this->handler_telegram_bot = $handler_telegram_bot;

        $this->http_client = new Client([
            'base_uri' => static::API_URL,
            'handler'  => $this->handler,
        ]);
    }

    /**
     * Cleans the address.
     *
     * @param string $address The address
     *
     * @return mixed
     * @throws Exception
     */
    public function cleanAddress(string $address)
    {
        try {
            $response = $this->http_client->post('clean/address', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Token ' . static::TOKEN,
                    'X-Secret'      => static::SECRET,
                ],
                'body'    => json_encode([$address]),
            ]);

            if ($response->getStatusCode() === 200) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents());

                return $result[0]->result;
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
            $result = json_decode($stream->getContents());

            $message = '<b>dadata.ru can\'t process request</b>' . PHP_EOL;
            $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
            $message .= 'Status code: ' . $exception->getResponse()->getStatusCode() . PHP_EOL;
            $message .= 'Reason phrase: ' . $exception->getResponse()->getReasonPhrase() . PHP_EOL;
            $message .= 'Error message: ' . $result->detail;

            $telegram = new Telegram(
                Config::TELEGRAM_DEV_TOKEN,
                Config::TELEGRAM_DEV_CHAT_ID,
                $this->handler_telegram_bot
            );
            $telegram->sendMessage($message);
        }
    }
}
