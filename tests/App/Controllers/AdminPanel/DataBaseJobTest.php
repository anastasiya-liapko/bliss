<?php

namespace Tests\App\Controllers\AdminPanel;

use App\Controllers\AdminPanel\AdminPanel;
use App\Controllers\AdminPanel\DataBaseJob;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class DataBaseJobTest.
 *
 * @package Tests\App\Controllers\AdminPanel
 */
class DataBaseJobTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(AdminPanel::class, new DataBaseJob([], new Session(), new Request()));
    }

    /**
     * Tests the updateAction method.
     *
     * @return void
     */
    public function testUpdateAction(): void
    {
        $this->assertTrue(method_exists(DataBaseJob::class, 'updateAction'));
    }

    /**
     * Tests the hasNotCompletedMigrationsAction method.
     *
     * @return void
     */
    public function testHasNotCompletedMigrationsAction(): void
    {
        $this->assertTrue(method_exists(DataBaseJob::class, 'hasNotCompletedMigrationsAction'));
    }

    /**
     * Tests the cleanUpAction method.
     *
     * @return void
     */
    public function testCleanUpAction(): void
    {
        $this->assertTrue(method_exists(DataBaseJob::class, 'cleanUpAction'));
    }
}
