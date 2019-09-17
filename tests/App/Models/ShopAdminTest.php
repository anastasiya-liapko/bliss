<?php

namespace Tests\App\Models;

use App\Models\ShopAdmin;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ShopAdminTest.
 *
 * @package Tests\App\Models
 */
class ShopAdminTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     * @throws \Exception
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new ShopAdmin());
    }

    /**
     * Tests the validate method.
     *
     * @return void
     */
    public function testValidate(): void
    {
        $this->assertTrue(method_exists(ShopAdmin::class, 'validate'));
    }

    /**
     * Tests the getIsActivated method.
     *
     * @return void
     */
    public function testGetIsActivated(): void
    {
        /** @var ShopAdmin|MockObject $stub */
        $stub = $this->getMockBuilder(ShopAdmin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getIsActivated());
    }

    /**
     * Tests the getShopId method.
     *
     * @return void
     */
    public function testGetShopId(): void
    {
        /** @var ShopAdmin|MockObject $stub */
        $stub = $this->getMockBuilder(ShopAdmin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getShopId());
    }

    /**
     * Tests the getRole method.
     *
     * @return void
     */
    public function testGetRole(): void
    {
        /** @var ShopAdmin|MockObject $stub */
        $stub = $this->getMockBuilder(ShopAdmin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getRole());
    }

    /**
     * Tests the getPhone method.
     *
     * @return void
     */
    public function testGetPhone(): void
    {
        /** @var ShopAdmin|MockObject $stub */
        $stub = $this->getMockBuilder(ShopAdmin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPhone());
    }

    /**
     * Tests the getPasswordHash method.
     *
     * @return void
     */
    public function testGetPasswordHash(): void
    {
        /** @var ShopAdmin|MockObject $stub */
        $stub = $this->getMockBuilder(ShopAdmin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPasswordHash());
    }

    /**
     * Tests the getPassword method.
     *
     * @return void
     */
    public function testGetPassword(): void
    {
        /** @var ShopAdmin|MockObject $stub */
        $stub = $this->getMockBuilder(ShopAdmin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPassword());
    }

    /**
     * Tests the getEmail method.
     *
     * @return void
     */
    public function testGetEmail(): void
    {
        /** @var ShopAdmin|MockObject $stub */
        $stub = $this->getMockBuilder(ShopAdmin::class)
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
        /** @var ShopAdmin|MockObject $stub */
        $stub = $this->getMockBuilder(ShopAdmin::class)
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
        /** @var ShopAdmin|MockObject $stub */
        $stub = $this->getMockBuilder(ShopAdmin::class)
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
        /** @var ShopAdmin|MockObject $stub */
        $stub = $this->getMockBuilder(ShopAdmin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the create method.
     *
     * @return ShopAdmin $shop_admin
     * @throws \Rakit\Validation\RuleQuashException
     * @throws \Exception
     */
    public function testCreate(): ShopAdmin
    {
        $shop_admin = new ShopAdmin($this->getConstructorData(['email' => 'petrov_pp@mail.ru']));
        $this->assertFalse($shop_admin->create(), 'Parameter email must be unique');

        $shop_admin = new ShopAdmin($this->getConstructorData());
        $this->assertTrue($shop_admin->create());

        return $shop_admin;
    }

    /**
     * Tests the deleteById method.
     *
     * @param ShopAdmin $shop_admin
     *
     * @depends testCreate
     *
     * @return void
     * @throws \Exception
     */
    public function testDeleteById(ShopAdmin $shop_admin)
    {
        $this->assertTrue(ShopAdmin::deleteById($shop_admin->getId()));
    }

    /**
     * Tests the FindById method.
     *
     * @return void
     */
    public function testFindByID(): void
    {
        $shop_admin = ShopAdmin::findById(1);

        $this->assertIsObject($shop_admin);
        $this->assertObjectHasAttribute('errors', $shop_admin);
        $this->assertObjectHasAttribute('id', $shop_admin);
        $this->assertObjectHasAttribute('name', $shop_admin);
        $this->assertObjectHasAttribute('email', $shop_admin);
        $this->assertObjectHasAttribute('password', $shop_admin);
        $this->assertObjectHasAttribute('password_hash', $shop_admin);
        $this->assertObjectHasAttribute('phone', $shop_admin);
        $this->assertObjectHasAttribute('role', $shop_admin);
        $this->assertObjectHasAttribute('shop_id', $shop_admin);
        $this->assertObjectHasAttribute('is_activated', $shop_admin);
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
            'name'         => array_key_exists('name', $data) ? $data['name'] : 'Иванов Иван Иванович',
            'email'        => array_key_exists('email', $data) ? $data['email'] : 'ivanov_ii@mail.ru',
            'phone'        => array_key_exists('phone', $data) ? $data['phone'] : '+7(999)999-99-99',
            'role'         => array_key_exists('role', $data) ? $data['role'] : 'admin',
            'shop_id'      => array_key_exists('shop_id', $data) ? $data['shop_id'] : 1,
            'is_activated' => array_key_exists('is_activated', $data) ? $data['is_activated'] : 1,
        ];
    }
}
