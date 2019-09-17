<?php

namespace Tests\App;

use App\SMSRu;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class SMSRuTest.
 *
 * @package Tests\App
 */
class SMSRuTest extends TestCase
{
    /**
     * Tests the sendAlarmByTelegramBot method.
     *
     * @return void
     */
    public function testSendAlarmByTelegramBot(): void
    {
        $this->assertTrue(method_exists(SMSRu::class, 'sendAlarmByTelegramBot'));
    }

    /**
     * Tests the send method.
     *
     * @return void
     * @throws \Exception
     */
    public function testSend(): void
    {
        $mock_telegram_bot = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler_telegram_bot = HandlerStack::create($mock_telegram_bot);

        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"status":"OK","status_code":100,"sms":{"79097391754":{"status":"OK","status_code":100,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
            new Response(
                200,
                [],
                '{"status":"","status_code":200,"sms":{"79097391754":{"status":"","status_code":200,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
            new Response(
                200,
                [],
                '{"status":"","status_code":201,"sms":{"79097391754":{"status":"","status_code":201,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
            new Response(
                200,
                [],
                '{"status":"","status_code":206,"sms":{"79097391754":{"status":"","status_code":206,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
            new Response(
                200,
                [],
                '{"status":"","status_code":220,"sms":{"79097391754":{"status":"","status_code":220,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
            new Response(
                200,
                [],
                '{"status":"","status_code":230,"sms":{"79097391754":{"status":"","status_code":230,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
            new Response(
                200,
                [],
                '{"status":"","status_code":231,"sms":{"79097391754":{"status":"","status_code":231,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
            new Response(
                200,
                [],
                '{"status":"","status_code":232,"sms":{"79097391754":{"status":"","status_code":232,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
            new Response(
                200,
                [],
                '{"status":"","status_code":300,"sms":{"79097391754":{"status":"","status_code":300,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
            new Response(
                200,
                [],
                '{"status":"","status_code":500,"sms":{"79097391754":{"status":"","status_code":500,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
            new RequestException('Error Communicating with Server', new Request('GET', 'send')),
        ]);

        $handler = HandlerStack::create($mock);
        $sms_ru  = new SMSRu($handler, $handler_telegram_bot);

        $this->assertTrue($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));
        $this->assertFalse($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));
        $this->assertFalse($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));
        $this->assertFalse($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));
        $this->assertFalse($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));
        $this->assertFalse($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));
        $this->assertFalse($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));
        $this->assertFalse($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));
        $this->assertFalse($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));
        $this->assertFalse($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));
        $this->assertFalse($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));

        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"status":"","status_code":500,"sms":{"79097391754":{"status":"","status_code":500,'
                . '"sms_id":"201912-1000033"}},"balance":47.22}'
            ),
            new RequestException('Error Communicating with Server', new Request('GET', 'send')),
        ]);

        $handler = HandlerStack::create($mock);
        $sms_ru  = new SMSRu($handler, $handler_telegram_bot);

        $this->assertFalse($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));
        $this->assertFalse($sms_ru->send('79097391754', 'Hi! It\'s auto test.', 1));
    }
}
