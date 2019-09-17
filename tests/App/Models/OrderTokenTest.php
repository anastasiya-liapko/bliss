<?php

namespace Tests\App\Models;

use App\Models\OrderToken;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderTokenTest.
 *
 * @package Tests\App\Models
 */
class OrderTokenTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     * @throws \Exception
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new OrderToken());
    }

    /**
     * Tests the getProcessOrderLink method.
     *
     * @return void
     */
    public function testGetProcessOrderLink(): void
    {
        /** @var OrderToken|MockObject $stub */
        $stub = $this->getMockBuilder(OrderToken::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getProcessOrderLink());
    }

    /**
     * Tests the getClientPhone method.
     *
     * @return void
     */
    public function testGetClientPhone(): void
    {
        /** @var OrderToken|MockObject $stub */
        $stub = $this->getMockBuilder(OrderToken::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getClientPhone());
    }

    /**
     * Tests the getOrderId method.
     *
     * @return void
     */
    public function testGetOrderId(): void
    {
        /** @var OrderToken|MockObject $stub */
        $stub = $this->getMockBuilder(OrderToken::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getOrderId());
    }

    /**
     * Tests the getTokenHash method.
     *
     * @return void
     */
    public function testGetTokenHash(): void
    {
        /** @var OrderToken|MockObject $stub */
        $stub = $this->getMockBuilder(OrderToken::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getTokenHash());
    }

    /**
     * Tests the getToken method.
     *
     * @return void
     */
    public function testGetToken(): void
    {
        /** @var OrderToken|MockObject $stub */
        $stub = $this->getMockBuilder(OrderToken::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getToken());
    }

    /**
     * Tests the getErrors method.
     *
     * @return void
     */
    public function testGetErrors(): void
    {
        /** @var OrderToken|MockObject $stub */
        $stub = $this->getMockBuilder(OrderToken::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the create method.
     *
     * @return void
     * @throws \Exception
     */
    public function testCreate(): void
    {
        $order_token = new OrderToken([
            'order_id'     => 9,
            'client_phone' => '+7(909)739-17-54',
        ]);
        $this->assertTrue($order_token->create());
    }
}
