<?php

namespace Tests\Core;

use Core\Error;
use Exception;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class ConnectorTest.
 *
 * @package Tests\Core
 */
class ErrorTest extends TestCase
{
    /**
     * Tests the sendException method.
     *
     * @return void
     * @throws Exception
     */
    public function testSendException(): void
    {
        $mock_telegram_bot = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}')
        ]);

        $handler_telegram_bot = HandlerStack::create($mock_telegram_bot);

        Error::sendException(new Exception(), $handler_telegram_bot);

        $this->assertTrue(true);
    }

    /**
     * Tests the logException method.
     *
     * @return void
     * @throws Exception
     */
    public function testLogException(): void
    {
        Error::logException(new Exception(), 500);

        $this->assertTrue(true);
    }

    /**
     * Tests the exceptionHandler method.
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testExceptionHandler(): void
    {
        $mock_telegram_bot = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}')
        ]);

        $handler_telegram_bot = HandlerStack::create($mock_telegram_bot);

        ob_start();
        Error::exceptionHandler(new Exception(), $handler_telegram_bot, true);
        $this->assertNotEmpty($this->getActualOutput());
        ob_end_clean();

        ob_start();
        Error::exceptionHandler(new Exception(), $handler_telegram_bot, false);
        $this->assertNotEmpty($this->getActualOutput());
        ob_end_clean();
    }

    /**
     * Tests the errorHandler method.
     *
     * @return void
     * @throws \ErrorException
     */
    public function testErrorHandler(): void
    {
        error_reporting(0);

        Error::errorHandler(1, 'test', 'test.php', 1);

        error_reporting(E_ALL);

        $this->expectException(Exception::class);

        Error::errorHandler(1, 'test', 'test.php', 1);
    }
}
