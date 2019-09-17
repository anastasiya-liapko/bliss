<?php

namespace App;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;

/**
 * Class MailMan.
 *
 * @package App
 */
class MailMan
{
    /**
     * The api url.
     *
     * @var string
     */
    const API_URL = 'http://mailman.alef.im/api/ml-single-send.php';

    /**
     * The api key.
     *
     * @var string
     */
    const API_KEY = '22ac3112bb6da2bb5b7a3e350f71af31';

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
     * Mail constructor.
     *
     * @param HandlerStack $handler (optional)
     * @param HandlerStack $handler_telegram_bot (optional)
     *
     * @throws Exception
     */
    public function __construct($handler = null, $handler_telegram_bot = null)
    {
        $file_name                  = 'mail/' . date('Y-m-d') . '.log';
        $this->handler              = $handler ?? Logging::createLoggingHandlerStack('Mail', $file_name);
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
     * Send a message.
     *
     * @param string $to The recipient.
     * @param string $subject The subject.
     * @param string $text Text-only content of the message.
     * @param string $html HTML content of the message.
     *
     * @return bool True if success, false otherwise.
     * @throws Exception
     */
    public function send(string $to, string $subject, string $text, string $html): bool
    {
        try {
            $response = $this->http_client->get('', [
                'query' => [
                    'api_key'   => static::API_KEY,
                    'to'        => $to,
                    'subject'   => $subject,
                    'html_body' => $html,
                    'timestamp' => time(),
                    // TODO add $text to request, when mailman api will be ready.
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents());

                if ($result->status === 0) {
                    return true;
                }

                $this->sendWarningMessageByTelegramBot($result->message);
            }
        } catch (Exception $e) {
            $this->sendExceptionMessageByTelegramBot($e);
        }

        return false;
    }

    /**
     * Sends the exception message by the Telegram bot.
     *
     * @param Exception $exception
     *
     * @return void
     * @throws Exception
     */
    private function sendExceptionMessageByTelegramBot(Exception $exception): void
    {
        if ($exception instanceof RequestException) {
            $message = '<b>MailMan can\'t process request</b>' . PHP_EOL;
            $message .= 'Host: ' . SiteInfo::getSchemeAndHttpHost() . PHP_EOL;
            $message .= 'Status code: ' . $exception->getResponse()->getStatusCode() . PHP_EOL;
            $message .= 'Reason phrase: ' . $exception->getResponse()->getReasonPhrase() . PHP_EOL;
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

    /**
     * Sends the warning by the Telegram bot.
     *
     * @param string $message The message.
     *
     * @return void
     * @throws Exception
     */
    private function sendWarningMessageByTelegramBot(string $message): void
    {
        $telegram = new Telegram(
            Config::TELEGRAM_DEV_TOKEN,
            Config::TELEGRAM_DEV_CHAT_ID,
            $this->handler_telegram_bot
        );
        $telegram->sendMessage($message);
    }
}
