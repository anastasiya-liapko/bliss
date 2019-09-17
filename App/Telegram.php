<?php

namespace App;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

/**
 * Class TelegramBot.
 *
 * @package App
 */
class Telegram
{
    /**
     * The api url.
     *
     * @var string
     */
    const API_URL = 'https://api.telegram.org';

    /**
     * The token.
     *
     * @var string
     */
    private $token;

    /**
     * The chat id.
     *
     * @var string
     */
    private $chat_id;

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
     * TelegramBot constructor.
     *
     * @param string $token
     * @param string $chat_id
     * @param HandlerStack $handler (optional)
     *
     * @throws Exception
     */
    public function __construct(string $token, string $chat_id, HandlerStack $handler = null)
    {
        $this->token   = $token;
        $this->chat_id = $chat_id;
        $file_name     = 'telegram/' . date('Y-m-d') . '.log';
        $this->handler = $handler ?? Logging::createLoggingHandlerStack('Telegram', $file_name);
    }

    /**
     * Sends message.
     *
     * @param string $message The message.
     *
     * @return bool True if success, false otherwise.
     * @throws Exception
     */
    public function sendMessage(string $message): bool
    {
        $this->http_client = new Client([
            'base_uri' => static::API_URL,
            'handler'  => $this->handler,
        ]);

        try {
            $response = $this->http_client->get('/bot' . $this->token . '/sendMessage', [
                'query' => [
                    'parse_mode' => 'HTML',
                    'chat_id'    => $this->chat_id,
                    'text'       => $message,
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $stream = $response->getBody();
                $stream->rewind();
                $result = json_decode($stream->getContents());

                return (bool)$result->ok;
            }
        } catch (Exception $e) {
            // I'm busy doing nothing.
        }

        return false;
    }
}
