<?php

namespace Tests\App\Controllers\Api\V1;

use Core\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ApiTest.
 *
 * @package Tests\App\Controllers\Api\V1
 */
class ApiTest extends TestCase
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
        $this->stack = $this->getMockForAbstractClass('App\Controllers\Api\V1\Api', [[], new Session(), new Request()]);
    }

    /**
     * Tests the apiExceptionHandler method.
     *
     * @return void
     */
    public function testApiExceptionHandler(): void
    {
        $this->assertTrue(method_exists($this->stack, 'apiExceptionHandler'));
    }

    /**
     * Tests the getBearerToken method.
     *
     * @return void
     */
    public function testGetBearerToken(): void
    {
        $this->assertTrue(method_exists($this->stack, 'getBearerToken'));
    }

    /**
     * Tests the checkMethods method.
     *
     * @return void
     */
    public function testCheckMethods(): void
    {
        $this->assertTrue(method_exists($this->stack, 'checkMethods'));
    }

    /**
     * Tests the maybeFindShopIdByToken method.
     *
     * @return void
     */
    public function testMaybeFindShopIdByToken(): void
    {
        $this->assertTrue(method_exists($this->stack, 'maybeFindShopIdByToken'));
    }
}
