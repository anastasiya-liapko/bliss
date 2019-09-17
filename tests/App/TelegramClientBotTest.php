<?php

namespace Tests\App;

use App\TelegramClientBot;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class TelegramClientBotTest.
 *
 * @package Tests\App
 */
class TelegramClientBotTest extends TestCase
{
    /**
     * Tests the clientCanceledLoanUponReceipt method.
     *
     * @return void
     * @throws \Exception
     */
    public function testClientCanceledLoanUponReceipt(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_client_bot = new TelegramClientBot($handler);
        $telegram_client_bot->clientCanceledLoanUponReceipt(
            'Петров Пётр Петрович',
            '79097391754',
            'ИП «Петров П.П.»',
            3000,
            1
        );

        $this->assertTrue(true);
    }

    /**
     * Tests the clientConfirmedLoan method.
     *
     * @return void
     * @throws \Exception
     */
    public function testClientConfirmedLoan(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_client_bot = new TelegramClientBot($handler);
        $telegram_client_bot->clientConfirmedLoan(
            'Петров Пётр Петрович',
            '79097391754',
            'ИП «Петров П.П.»',
            3000,
            1
        );

        $this->assertTrue(true);
    }

    /**
     * Tests the clientWaitingForLimit method.
     *
     * @return void
     * @throws \Exception
     */
    public function testClientWaitingForLimit(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_client_bot = new TelegramClientBot($handler);
        $telegram_client_bot->clientWaitingForLimit(
            'Петров Пётр Петрович',
            '79097391754',
            'ИП «Петров П.П.»',
            3000,
            1
        );

        $this->assertTrue(true);
    }

    /**
     * Tests the clientCanceledRequest method.
     *
     * @return void
     * @throws \Exception
     */
    public function testClientCanceledRequest(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_client_bot = new TelegramClientBot($handler);
        $telegram_client_bot->clientCanceledRequest(
            'Петров Пётр Петрович',
            '79097391754',
            'ИП «Петров П.П.»',
            3000,
            1
        );

        $this->assertTrue(true);
    }

    /**
     * Tests the clientCreatedRequest method.
     *
     * @return void
     * @throws \Exception
     */
    public function testClientCreatedRequest(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_client_bot = new TelegramClientBot($handler);
        $telegram_client_bot->clientCreatedRequest(
            'Петров Пётр Петрович',
            '79097391754',
            'ИП «Петров П.П.»',
            3000,
            1
        );

        $this->assertTrue(true);
    }


    /**
     * Tests the clientConfirmedPhoneNumber method.
     *
     * @return void
     * @throws \Exception
     */
    public function testClientConfirmedPhoneNumber(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler = HandlerStack::create($mock);

        $telegram_client_bot = new TelegramClientBot($handler);
        $telegram_client_bot->clientConfirmedPhoneNumber(
            '79097391754',
            'ИП «Петров П.П.»',
            3000
        );

        $this->assertTrue(true);
    }
}
