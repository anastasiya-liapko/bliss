<?php

namespace Tests\App\Controllers\Api\V1;

use App\Controllers\Api\V1\Api;
use App\Controllers\Api\V1\Shops;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ShopsTest.
 *
 * @package Tests\App\Controllers\ShopAdminPanel
 */
class ShopsTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Api::class, new Shops([], new Session(), new Request()));
    }

    /**
     * Tests the validateTokenRequest method.
     *
     * @return void
     */
    public function testValidateTokenRequest(): void
    {
        $this->assertTrue(method_exists(Shops::class, 'validateTokenRequest'));
    }

    /**
     * Tests the tokenAction method.
     *
     * @return void
     */
    public function testTokenAction(): void
    {
        $this->assertTrue(method_exists(Shops::class, 'tokenAction'));
    }
}
