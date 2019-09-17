<?php

namespace Tests\App\Controllers\Api\V1;

use App\Controllers\Api\V1\Api;
use App\Controllers\Api\V1\DeliveryServices;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class DeliveryServicesTest.
 *
 * @package Tests\App\Controllers\ShopAdminPanel
 */
class DeliveryServicesTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Api::class, new DeliveryServices([], new Session(), new Request()));
    }

    /**
     * Tests the indexAction method.
     *
     * @return void
     */
    public function testTokenAction(): void
    {
        $this->assertTrue(method_exists(DeliveryServices::class, 'indexAction'));
    }
}
