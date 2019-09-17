<?php

namespace Tests\App\Models;

use App\SMS;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class SMSTest.
 *
 * @package Tests\App
 */
class SMSTest extends TestCase
{
    /**
     * Tests the send method.
     *
     * @return void
     * @throws \Exception
     */
    public function testSend(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"status":"OK","status_code":100,"sms":{"79097391754":{"status":"OK","status_code":100,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
        ]);

        $handler = HandlerStack::create($mock);

        $this->assertTrue(SMS::send('79097391754', 'Hi! It\'s auto test.', $handler));
    }

    /**
     * Tests the sendRememberedClientLink method.
     *
     * @return void
     * @throws \Exception
     */
    public function testSendRememberedClientLink(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"status":"OK","status_code":100,"sms":{"79097391754":{"status":"OK","status_code":100,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
        ]);

        $handler = HandlerStack::create($mock);

        $this->assertTrue(SMS::sendRememberedClientLink(
            '79097391754',
            '6d4cde3960ef794c4010e7719b71608fbecfc709825acf215585d61796e57568',
            $handler
        ));
    }

    /**
     * Tests the sendOrderLink method.
     *
     * @return void
     * @throws \Exception
     */
    public function testSendOrderLink(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"status":"OK","status_code":100,"sms":{"79097391754":{"status":"OK","status_code":100,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
        ]);

        $handler = HandlerStack::create($mock);

        $this->assertTrue(SMS::sendOrderLink(
            '79097391754',
            'ИП "Петров П.П."',
            'c887ecd8cb0e4e7bc0df3104a29946563b0264daa278a581fdf88dfa208428df',
            $handler
        ));
    }
}
