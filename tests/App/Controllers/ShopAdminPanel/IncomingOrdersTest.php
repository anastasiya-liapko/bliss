<?php

namespace Tests\App\Controllers\ShopAdminPanel;

use App\Controllers\ShopAdminPanel\IncomingOrders;
use App\Controllers\ShopAdminPanel\ShopAdminPanel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class IncomingOrdersTest.
 *
 * @package Tests\App\Controllers\ShopAdminPanel
 */
class IncomingOrdersTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(ShopAdminPanel::class, new IncomingOrders([], new Session(), new Request()));
    }

    /**
     * Tests the validateCreateRequest method.
     *
     * @return void
     */
    public function testValidateCreateRequest(): void
    {
        $this->assertTrue(method_exists(IncomingOrders::class, 'validateCreateRequest'));
    }

    /**
     * Tests the validateDeliverRequest method.
     *
     * @return void
     */
    public function testValidateDeliverRequest(): void
    {
        $this->assertTrue(method_exists(IncomingOrders::class, 'validateDeliverRequest'));
    }

    /**
     * Tests the createAction method.
     *
     * @return void
     */
    public function testCreateAction(): void
    {
        $this->assertTrue(method_exists(IncomingOrders::class, 'createAction'));
    }

    /**
     * Tests the issueAction method.
     *
     * @return void
     */
    public function testIssueAction(): void
    {
        $this->assertTrue(method_exists(IncomingOrders::class, 'issueAction'));
    }

    /**
     * Tests the deliverAction method.
     *
     * @return void
     */
    public function testDeliverAction(): void
    {
        $this->assertTrue(method_exists(IncomingOrders::class, 'deliverAction'));
    }

    /**
     * Tests the declineAction method.
     *
     * @return void
     */
    public function testDeclineAction(): void
    {
        $this->assertTrue(method_exists(IncomingOrders::class, 'declineAction'));
    }

    /**
     * Tests the getCreatedOrdersAction method.
     *
     * @return void
     */
    public function testGetCreatedOrdersAction(): void
    {
        $this->assertTrue(method_exists(IncomingOrders::class, 'getCreatedOrdersAction'));
    }

    /**
     * Tests the getPotentialOrdersAction method.
     *
     * @return void
     */
    public function testGetPotentialOrdersAction(): void
    {
        $this->assertTrue(method_exists(IncomingOrders::class, 'getPotentialOrdersAction'));
    }

    /**
     * Tests the getPendingOrdersAction method.
     *
     * @return void
     */
    public function testGetPendingOrdersAction(): void
    {
        $this->assertTrue(method_exists(IncomingOrders::class, 'getPendingOrdersAction'));
    }

    /**
     * Tests the getDeliveryServicesAction method.
     *
     * @return void
     */
    public function testGetDeliveryServicesAction(): void
    {
        $this->assertTrue(method_exists(IncomingOrders::class, 'getDeliveryServicesAction'));
    }
}
