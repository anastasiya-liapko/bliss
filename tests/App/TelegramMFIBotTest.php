<?php

namespace Tests\App;

use App\TelegramMFIBot;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class TelegramMFIBotTest.
 *
 * @package Tests\App
 */
class TelegramMFIBotTest extends TestCase
{
    /**
     * Tests the allMfiDeclined method.
     *
     * @return void
     * @throws \Exception
     */
    public function testAllMfiDeclined(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_mfi_bot = new TelegramMFIBot($handler);
        $telegram_mfi_bot->allMfiDeclined(1);

        $this->assertTrue(true);
    }

    /**
     * Tests the didNotHaveTime method.
     *
     * @return void
     * @throws \Exception
     */
    public function testDidNotHaveTime(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_mfi_bot = new TelegramMFIBot($handler);
        $telegram_mfi_bot->didNotHaveTime(1);

        $this->assertTrue(true);
    }

    /**
     * Tests the mfiGaveResponse method.
     *
     * @return void
     * @throws \Exception
     */
    public function testMfiGaveResponse(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_mfi_bot = new TelegramMFIBot($handler);
        $telegram_mfi_bot->mfiGaveResponse('Веббанкир', 1, 'МФО одобрила кредит');

        $this->assertTrue(true);
    }
}
