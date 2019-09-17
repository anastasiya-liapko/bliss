<?php

namespace Tests\App;

use App\Config;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest.
 *
 * @package Tests\App
 */
class ConfigTest extends TestCase
{
    /**
     * Tests the isLocalServer method.
     *
     * @return void
     */
    public function testIsLocalServer(): void
    {
        $this->assertIsBool(Config::isLocalServer());
    }

    /**
     * Tests the isDevServer method.
     *
     * @return void
     */
    public function testIsDevServer(): void
    {
        $this->assertIsBool(Config::isDevServer());
    }
}
