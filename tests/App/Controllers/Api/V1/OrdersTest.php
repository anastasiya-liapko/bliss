<?php

namespace Tests\App\Controllers\Api\V1;

use App\Controllers\Api\V1\Api;
use App\Controllers\Api\V1\Orders;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class OrdersTest.
 *
 * @package Tests\App\Controllers\ShopAdminPanel
 */
class OrdersTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Api::class, new Orders([], new Session(), new Request()));
    }

    /**
     * Tests the validateDeliverRequest method.
     *
     * @return void
     */
    public function testValidateDeliverRequest(): void
    {
        $this->assertTrue(method_exists(Orders::class, 'validateDeliverRequest'));
    }

    /**
     * Tests the validateGetOrdersRequest method.
     *
     * @return void
     */
    public function testValidateGetOrdersRequest(): void
    {
        $this->assertTrue(method_exists(Orders::class, 'validateGetOrdersRequest'));
    }

    /**
     * Tests the validateCreateRequest method.
     *
     * @return void
     */
    public function testValidateCreateRequest(): void
    {
        $this->assertTrue(method_exists(Orders::class, 'validateCreateRequest'));
    }

    /**
     * Tests the sendOrderLink method.
     *
     * @return void
     */
    public function testSendOrderLink(): void
    {
        $this->assertTrue(method_exists(Orders::class, 'sendOrderLink'));
    }

    /**
     * Tests the maybeGetOrders method.
     *
     * @return void
     */
    public function testMaybeGetOrders(): void
    {
        $this->assertTrue(method_exists(Orders::class, 'maybeGetOrders'));
    }

    /**
     * Tests the maybeCreateOrder method.
     *
     * @return void
     */
    public function testMaybeCreateOrder(): void
    {
        $this->assertTrue(method_exists(Orders::class, 'maybeCreateOrder'));
    }

    /**
     * Tests the deliverAction method.
     *
     * @return void
     */
    public function testDeliverAction(): void
    {
        $this->assertTrue(method_exists(Orders::class, 'deliverAction'));
    }

    /**
     * Tests the declineAction method.
     *
     * @return void
     */
    public function testDeclineAction(): void
    {
        $this->assertTrue(method_exists(Orders::class, 'declineAction'));
    }

    /**
     * Tests the confirmAction method.
     *
     * @return void
     */
    public function testConfirmAction(): void
    {
        $this->assertTrue(method_exists(Orders::class, 'confirmAction'));
    }

    /**
     * Tests the indexAction method.
     *
     * @return void
     */
    public function testIndexAction(): void
    {
        $this->assertTrue(method_exists(Orders::class, 'indexAction'));
    }
}
