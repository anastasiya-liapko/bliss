<?php

namespace Tests\App\Models;

use App\Models\Shop;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ShopTest.
 *
 * @package Tests\App\Models
 */
class ShopTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     * @throws \Exception
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new Shop());
    }

    /**
     * Tests the validate method.
     *
     * @return void
     */
    public function testValidate(): void
    {
        $this->assertTrue(method_exists(Shop::class, 'validate'));
    }

    /**
     * Tests the getOrganizationId method.
     *
     * @return void
     */
    public function testGetOrganizationId(): void
    {
        /** @var Shop|MockObject $stub */
        $stub = $this->getMockBuilder(Shop::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getOrganizationId());
    }

    /**
     * Tests the getSecretKey method.
     *
     * @return void
     */
    public function testGetSecretKey(): void
    {
        /** @var Shop|MockObject $stub */
        $stub = $this->getMockBuilder(Shop::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getSecretKey());
    }

    /**
     * Tests the getIsActivated method.
     *
     * @return void
     */
    public function testGetIsActivated(): void
    {
        /** @var Shop|MockObject $stub */
        $stub = $this->getMockBuilder(Shop::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getIsActivated());
    }

    /**
     * Tests the getEmail method.
     *
     * @return void
     */
    public function testGetEmail(): void
    {
        /** @var Shop|MockObject $stub */
        $stub = $this->getMockBuilder(Shop::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getEmail());
    }

    /**
     * Tests the getName method.
     *
     * @return void
     */
    public function testGetName(): void
    {
        /** @var Shop|MockObject $stub */
        $stub = $this->getMockBuilder(Shop::class)
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
        /** @var Shop|MockObject $stub */
        $stub = $this->getMockBuilder(Shop::class)
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
        /** @var Shop|MockObject $stub */
        $stub = $this->getMockBuilder(Shop::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the create method.
     *
     * @return Shop $shop
     * @throws \Rakit\Validation\RuleQuashException
     * @throws \Exception
     */
    public function testCreate(): Shop
    {
        $shop = new Shop($this->getConstructorData(['email' => 'petrov_pp@mail.ru']));
        $this->assertFalse($shop->create(), 'Parameter email must be unique');

        $shop = new Shop($this->getConstructorData());

        $this->assertTrue($shop->create());

        return $shop;
    }

    /**
     * Tests the deleteById method.
     *
     * @param Shop $shop
     *
     * @depends testCreate
     *
     * @return void
     */
    public function testDeleteById(Shop $shop)
    {
        $this->assertTrue(Shop::deleteById($shop->getId()));
    }

    /**
     * Tests the findById method.
     *
     * @return void
     */
    public function testFindById(): void
    {
        $shop = Shop::findById(1);

        $this->assertIsObject($shop);

        $this->assertObjectHasAttribute('errors', $shop);
        $this->assertObjectHasAttribute('id', $shop);
        $this->assertObjectHasAttribute('name', $shop);
        $this->assertObjectHasAttribute('email', $shop);
        $this->assertObjectHasAttribute('is_activated', $shop);
        $this->assertObjectHasAttribute('secret_key', $shop);
        $this->assertObjectHasAttribute('organization_id', $shop);
    }

    /**
     * Get the constructor data.
     *
     * @param array $data The data
     *
     * @return array The constructor data.
     */
    private function getConstructorData(array $data = []): array
    {
        return [
            'name'            => array_key_exists('name', $data) ? $data['name'] : 'ИП "Иванов И. И."',
            'email'           => array_key_exists('email', $data) ? $data['email'] : 'ivanov_ii@mail.ru',
            'is_activated'    => array_key_exists('is_activated', $data) ? $data['is_activated'] : 0,
            'organization_id' => array_key_exists('organization_id', $data) ? $data['organization_id'] : 1,
        ];
    }
}
