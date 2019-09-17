<?php

namespace Tests\App\Controllers\ShopAdminPanel;

use App\Controllers\ShopAdminPanel\FailedOrders;
use App\Controllers\ShopAdminPanel\ShopAdminPanel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class FailedOrdersTest.
 *
 * @package Tests\App\Controllers\ShopAdminPanel
 */
class FailedOrdersTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(ShopAdminPanel::class, new FailedOrders([], new Session(), new Request()));
    }

    /**
     * Tests the getFailedOrdersAction method.
     *
     * @return void
     */
    public function testGetFailedOrdersAction(): void
    {
        $this->assertTrue(method_exists(FailedOrders::class, 'getFailedOrdersAction'));
    }
}
