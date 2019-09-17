<?php

namespace Tests\App\Models;

use App\Models\Partner;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class PartnerTest.
 *
 * @package Tests\App\Models
 */
class PartnerTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new Partner([]));
    }

    /**
     * Tests the getOrderby method.
     *
     * @return void
     */
    public function testGetOrderby(): void
    {
        /** @var Partner|MockObject $stub */
        $stub = $this->getMockBuilder(Partner::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getOrderby());
    }

    /**
     * Tests the getUrl method.
     *
     * @return void
     */
    public function testGetUrl(): void
    {
        /** @var Partner|MockObject $stub */
        $stub = $this->getMockBuilder(Partner::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getUrl());
    }

    /**
     * Tests the getImgUrl method.
     *
     * @return void
     */
    public function testGetImgUrl(): void
    {
        /** @var Partner|MockObject $stub */
        $stub = $this->getMockBuilder(Partner::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getImgUrl());
    }

    /**
     * Tests the getName method.
     *
     * @return void
     */
    public function testGetName(): void
    {
        /** @var Partner|MockObject $stub */
        $stub = $this->getMockBuilder(Partner::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getName());
    }

    /**
     * Tests the getId method.
     *
     * @return void
     */
    public function testGetId(): void
    {
        /** @var Partner|MockObject $stub */
        $stub = $this->getMockBuilder(Partner::class)
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
        /** @var Partner|MockObject $stub */
        $stub = $this->getMockBuilder(Partner::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the create method.
     *
     * @return Partner $partner
     */
    public function testCreate(): Partner
    {
        $partner = new Partner([
            'name'    => 'Example',
            'img_url' => 'http://example.com',
            'url'     => 'http://example.com',
            'orderby' => 0,
        ]);
        $this->assertTrue($partner->create());

        return $partner;
    }

    /**
     * Tests the deleteById method.
     *
     * @param Partner $partner
     *
     * @depends testCreate
     *
     * @return void
     */
    public function testDeleteById(Partner $partner): void
    {
        $this->assertTrue(Partner::deleteById($partner->getId()));
    }

    /**
     * Tests the gerAll method.
     *
     * @depends testCreate
     *
     * @return void
     */
    public function testGetAll(): void
    {
        $this->assertIsArray(Partner::getAll());
    }
}
