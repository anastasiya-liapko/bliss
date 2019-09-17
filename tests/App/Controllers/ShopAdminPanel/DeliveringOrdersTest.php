<?php

namespace Tests\App\Controllers\ShopAdminPanel;

use App\Controllers\ShopAdminPanel\DeliveringOrders;
use App\Controllers\ShopAdminPanel\ShopAdminPanel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class DeliveringOrdersTest.
 *
 * @package Tests\App\Controllers\ShopAdminPanel
 */
class DeliveringOrdersTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(ShopAdminPanel::class, new DeliveringOrders([], new Session(), new Request()));
    }

    /**
     * Tests the issueAction method.
     *
     * @return void
     */
    public function testIssueAction(): void
    {
        $this->assertTrue(method_exists(DeliveringOrders::class, 'issueAction'));
    }

    /**
     * Tests the declineAction method.
     *
     * @return void
     */
    public function testDeclineAction(): void
    {
        $this->assertTrue(method_exists(DeliveringOrders::class, 'declineAction'));
    }

    /**
     * Tests the getAutoDeliveringOrdersAction method.
     *
     * @return void
     */
    public function testGetAutoDeliveringOrdersAction(): void
    {
        $this->assertTrue(method_exists(DeliveringOrders::class, 'getAutoDeliveringOrdersAction'));
    }

    /**
     * Tests the getManualDeliveringOrdersAction method.
     *
     * @return void
     */
    public function testGetManualDeliveringOrdersAction(): void
    {
        $this->assertTrue(method_exists(DeliveringOrders::class, 'getManualDeliveringOrdersAction'));
    }
}
