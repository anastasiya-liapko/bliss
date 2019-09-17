<?php

namespace Tests\App;

use App\Config;
use App\Telegram;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class TelegramTest.
 *
 * @package Tests\App
 */
class TelegramTest extends TestCase
{
    /**
     * Tests the sendMessage method.
     *
     * @return void
     * @throws \Exception
     */
    public function testSendMessage(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(500),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram = new Telegram(
            Config::TELEGRAM_DEV_TOKEN,
            Config::TELEGRAM_DEV_CHAT_ID,
            $handler
        );

        $this->assertTrue($telegram->sendMessage('Hi! It\'s auto test.'), 'Can\'t send message by Telegram');
        $this->assertFalse($telegram->sendMessage('Hi! It\'s auto test.'), 'Can\'t send message by Telegram');
    }
}
