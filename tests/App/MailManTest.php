<?php

namespace Tests\App;

use App\MailMan;
use App\SiteInfo;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class MailTest.
 *
 * @package Tests\App
 */
class MailManTest extends TestCase
{
    /**
     * Tests the sendWarningMessageByTelegramBot method.
     *
     * @return void
     */
    public function testSendWarningMessageByTelegramBot(): void
    {
        $this->assertTrue(method_exists(MailMan::class, 'sendWarningMessageByTelegramBot'));
    }

    /**
     * Tests the sendExceptionMessageByTelegramBot method.
     *
     * @return void
     */
    public function testSendExceptionMessageByTelegramBot(): void
    {
        $this->assertTrue(method_exists(MailMan::class, 'sendExceptionMessageByTelegramBot'));
    }

    /**
     * Tests the send method.
     *
     * @throws \Exception
     */
    public function testSend(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"status":0,"message":"Successfully sent email to info@bliss.ru","timestamp":1555494504}'
            ),
            new Response(
                200,
                [],
                '{"status":1,"message":"Error while trying to send email. See error_details for more'
                . ' information","error_details":"Trying to send identical request. If you are intended to do it, '
                . 'send different timestamp value","error_code":280}'
            ),
            new RequestException(
                'Error Communicating with Server',
                new Request('GET', ''),
                new Response(
                    500,
                    [],
                    '{"status":0,"message":"Error while trying to send email. See error_details for more'
                    . ' information","error_details":"Trying to send identical request. If you are intended to do it, '
                    . 'send different timestamp value","error_code":280}'
                )
            ),
        ]);

        $handler = HandlerStack::create($mock);

        $mock_telegram_bot = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler_telegram_bot = HandlerStack::create($mock_telegram_bot);

        $mail = new MailMan($handler, $handler_telegram_bot);

        $this->assertTrue(
            $mail->send(
                SiteInfo::getCreditingEmail(),
                'Hi! It\'s auto test.',
                'Hi! It\'s auto test.',
                '<h1>Hi! It\'s auto test.</h1>'
            )
        );
        $this->assertFalse(
            $mail->send(
                SiteInfo::getCreditingEmail(),
                'Hi! It\'s auto test.',
                'Hi! It\'s auto test.',
                '<h1>Hi! It\'s auto test.</h1>'
            )
        );
        $this->assertFalse(
            $mail->send(
                SiteInfo::getCreditingEmail(),
                'Hi! It\'s auto test.',
                'Hi! It\'s auto test.',
                '<h1>Hi! It\'s auto test.</h1>'
            )
        );
    }
}
