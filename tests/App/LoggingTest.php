<?php

namespace Tests\App;

use App\Logging;
use PHPUnit\Framework\TestCase;

/**
 * Class LoggingTest.
 *
 * @package Tests\App
 */
class LoggingTest extends TestCase
{
    /**
     * Tests the createLoggingHandlerStack method.
     *
     * @return void
     * @throws \Exception
     */
    public function testCreateLoggingHandlerStack(): void
    {
        $this->assertIsObject(Logging::createLoggingHandlerStack('test', 'test.log'));
    }
}
