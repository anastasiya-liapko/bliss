<?php

namespace Tests\App;

use App\Morpher;
use App\SMSRu;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class Morpher.
 *
 * @package Tests\App
 */
class MorpherTest extends TestCase
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
     * Tests the getInclinedWord method.
     *
     * @return void
     * @throws \Exception
     */
    public function testGetInclinedWord(): void
    {
        $mock_telegram_bot = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler_telegram_bot = HandlerStack::create($mock_telegram_bot);

        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"Р":"Иванова Ивана Ивановича","Д":"Иванову Ивану Ивановичу","В":"Иванова Ивана Ивановича",'
                . '"Т":"Ивановым Иваном Ивановичем","П":"Иванове Иване Ивановиче","ФИО":{"Ф":"Иванов","И":"Иван",'
                . '"О":"Иванович"}}'
            ),
            new RequestException(
                'Error Communicating with Server',
                new Request('GET', 'getInclinedWord'),
                new Response(
                    497,
                    [],
                    '{"code":10,"message":"Неверный формат токена."}'
                )
            ),
        ]);

        $handler = HandlerStack::create($mock);
        $morpher = new Morpher($handler, $handler_telegram_bot);
        $this->assertIsObject($morpher->getInclinedWord('Иванов Иван Иванович', true));
        $this->assertFalse($morpher->getInclinedWord('Иванов Иван Иванович', true));

        $mock = new MockHandler([
            new RequestException(
                'Error Communicating with Server',
                new Request('GET', 'getInclinedWord'),
                new Response(
                    497,
                    [],
                    '{"code":10,"message":"Неверный формат токена."}'
                )
            ),
        ]);

        $handler = HandlerStack::create($mock);
        $morpher = new Morpher($handler, $handler_telegram_bot);
        $this->assertFalse($morpher->getInclinedWord('Иванов Иван Иванович', true));
    }
}
