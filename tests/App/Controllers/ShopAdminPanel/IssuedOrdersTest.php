<?php

namespace Tests\App\Controllers\ShopAdminPanel;

use App\Controllers\ShopAdminPanel\IssuedOrders;
use App\Controllers\ShopAdminPanel\ShopAdminPanel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class IssuedOrdersTest.
 *
 * @package Tests\App\Controllers\ShopAdminPanel
 */
class IssuedOrdersTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(ShopAdminPanel::class, new IssuedOrders([], new Session(), new Request()));
    }

    /**
     * Tests the getPaidOrdersAction method.
     *
     * @return void
     */
    public function testGetPaidOrdersAction(): void
    {
        $this->assertTrue(method_exists(IssuedOrders::class, 'getPaidOrdersAction'));
    }

    /**
     * Tests the getUnpaidOrdersAction method.
     *
     * @return void
     */
    public function testGetUnpaidOrdersAction(): void
    {
        $this->assertTrue(method_exists(IssuedOrders::class, 'getUnpaidOrdersAction'));
    }
}
