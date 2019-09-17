<?php

namespace Tests\App\Controllers\ShopAdminPanel;

use Core\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ShopAdminPanelTest.
 *
 * @package Tests\App\Controllers\ShopAdminPanel
 */
class ShopAdminPanelTest extends TestCase
{
    /**
     * The stack.
     *
     * @var Controller
     */
    protected $stack;

    /**
     * Sets up.
     */
    public function setUp(): void
    {
        $this->stack = $this->getMockForAbstractClass(
            'App\Controllers\ShopAdminPanel\ShopAdminPanel',
            [[], new Session(), new Request()]
        );
    }

    /**
     * Tests the validateDeclineRequest method.
     *
     * @return void
     */
    public function testValidateDeclineRequest(): void
    {
        $this->assertTrue(method_exists($this->stack, 'validateDeclineRequest'));
    }

    /**
     * Tests the validateIssueRequest method.
     *
     * @return void
     */
    public function testValidateIssueRequest(): void
    {
        $this->assertTrue(method_exists($this->stack, 'validateIssueRequest'));
    }

    /**
     * Tests the validateGetOrdersRequest method.
     *
     * @return void
     */
    public function testValidateGetOrdersRequest(): void
    {
        $this->assertTrue(method_exists($this->stack, 'validateGetOrdersRequest'));
    }

    /**
     * Tests the validateGetOrderRequest method.
     *
     * @return void
     */
    public function testValidateGetOrderRequest(): void
    {
        $this->assertTrue(method_exists($this->stack, 'validateGetOrderRequest'));
    }

    /**
     * Tests the getOrderAction method.
     *
     * @return void
     */
    public function testGetOrderAction(): void
    {
        $this->assertTrue(method_exists($this->stack, 'getOrderAction'));
    }

    /**
     * Tests the checkOrder method.
     *
     * @return void
     */
    public function testCheckRequest(): void
    {
        $this->assertTrue(method_exists($this->stack, 'checkOrder'));
    }
}
