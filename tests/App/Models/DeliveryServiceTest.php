<?php

namespace Tests\App\Models;

use App\Models\DeliveryService;
use Core\Model;
use PHPUnit\Framework\TestCase;

/**
 * Class DeliveryServiceTest.
 *
 * @package Tests\App\Models
 */
class DeliveryServiceTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     * @throws \Exception
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new DeliveryService());
    }

    /**
     * Tests the getAll method.
     *
     * @return void
     */
    public function testGetAll(): void
    {
        $delivery_services = DeliveryService::getAll();

        $this->assertIsArray($delivery_services);

        $this->assertEquals(1, $delivery_services[0]['id']);
        $this->assertEquals(
            'Другая (отслеживание и смена статуса производятся вручную)',
            $delivery_services[0]['name']
        );
        $this->assertEquals('default', $delivery_services[0]['slug']);
    }

    /**
     * Tests the getName method.
     *
     * @return void
     */
    public function testGetName(): void
    {
        $this->assertEquals(
            'Другая (отслеживание и смена статуса производятся вручную)',
            DeliveryService::getName(1)
        );
    }
}
