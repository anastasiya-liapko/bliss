<?php

namespace Tests\App\Models;

use App\Models\OrderCallback;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderCallbackTest.
 *
 * @package Tests\App\Models
 */
class OrderCallbackTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     * @throws \Exception
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new OrderCallback());
    }

    /**
     * Tests the validate method.
     *
     * @return void
     */
    public function testValidate(): void
    {
        $this->assertTrue(method_exists(OrderCallback::class, 'validate'));
    }

    /**
     * Tests the getIsCallbackSent method.
     *
     * @return void
     */
    public function testGetIsCallbackSent(): void
    {
        /** @var OrderCallback|MockObject $stub */
        $stub = $this->getMockBuilder(OrderCallback::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getIsCallbackSent());
    }

    /**
     * Tests the getCallbackUrl method.
     *
     * @return void
     */
    public function testGetCallbackUrl(): void
    {
        /** @var OrderCallback|MockObject $stub */
        $stub = $this->getMockBuilder(OrderCallback::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getCallbackUrl());
    }

    /**
     * Tests the getOrderId method.
     *
     * @return void
     */
    public function testGetOrderId(): void
    {
        /** @var OrderCallback|MockObject $stub */
        $stub = $this->getMockBuilder(OrderCallback::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getOrderId());
    }

    /**
     * Tests the getId method.
     *
     * @return void
     */
    public function testGetId(): void
    {
        /** @var OrderCallback|MockObject $stub */
        $stub = $this->getMockBuilder(OrderCallback::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getId());
    }

    /**
     * Tests the getErrors method.
     *
     * @return void
     */
    public function testGetErrors(): void
    {
        /** @var OrderCallback|MockObject $stub */
        $stub = $this->getMockBuilder(OrderCallback::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the updateIsCallbackSent method.
     *
     * @depends testCreate
     *
     * @param OrderCallback $order_callback
     *
     * @return void
     * @throws \Exception
     */
    public function testUpdateIsCallbackSent(OrderCallback $order_callback): void
    {
        $this->assertTrue($order_callback->updateIsCallbackSent(1));
    }

    /**
     * Tests the create method.
     *
     * @return OrderCallback $order_callback
     * @throws \Exception
     */
    public function testCreate(): OrderCallback
    {
        $order_callback = new OrderCallback([
            'order_id'     => 9,
            'callback_url' => 'https://example.com',
        ]);
        $this->assertTrue($order_callback->create());

        $this->assertFalse($order_callback->create());

        return $order_callback;
    }

    /**
     * Tests the findByOrderId method.
     *
     * @depends testCreate
     *
     * @return void
     * @throws \Exception
     */
    public function testFindByOrderId(): void
    {
        $order_callback = OrderCallback::findByOrderId(9);
        $this->assertIsObject($order_callback);

        $this->assertObjectHasAttribute('errors', $order_callback);
        $this->assertObjectHasAttribute('id', $order_callback);
        $this->assertObjectHasAttribute('order_id', $order_callback);
        $this->assertObjectHasAttribute('callback_url', $order_callback);
        $this->assertObjectHasAttribute('is_callback_sent', $order_callback);
    }

    /**
     * Tests the deleteById method.
     *
     * @depends testCreate
     *
     * @param OrderCallback $order_callback
     *
     * @return void
     * @throws \Exception
     */
    public function testDeleteByIdd(OrderCallback $order_callback): void
    {
        $this->assertTrue(OrderCallback::deleteById($order_callback->getId()));
    }
}
