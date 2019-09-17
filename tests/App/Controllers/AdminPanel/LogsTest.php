<?php

namespace Tests\App\Controllers\AdminPanel;

use App\Controllers\AdminPanel\AdminPanel;
use App\Controllers\AdminPanel\Logs;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class LogsTest.
 *
 * @package Tests\App\Controllers\AdminPanel
 */
class LogsTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(AdminPanel::class, new Logs([], new Session(), new Request()));
    }

    /**
     * Tests the downloadLogsAction method.
     *
     * @return void
     */
    public function testDownloadLogsAction(): void
    {
        $this->assertTrue(method_exists(Logs::class, 'downloadLogsAction'));
    }
}
