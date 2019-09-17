<?php

namespace Tests\App\Models;

use App\Models\Admin;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class AdminTest.
 *
 * @package Tests\App\Models
 */
class AdminTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     * @throws \Exception
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new Admin());
    }

    /**
     * Tests the getRole method.
     *
     * @return void
     */
    public function testGetRole(): void
    {
        /** @var Admin|MockObject $stub */
        $stub = $this->getMockBuilder(Admin::class)
                     ->setConstructorArgs([['role' => 'admin']])
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals('admin', $stub->getRole());
    }

    /**
     * Tests the getPasswordHash method.
     *
     * @return void
     */
    public function testGetPasswordHash(): void
    {
        /** @var Admin|MockObject $stub */
        $stub = $this->getMockBuilder(Admin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPasswordHash());
    }

    /**
     * Tests the getEmail method.
     *
     * @return void
     */
    public function testGetEmail(): void
    {
        /** @var Admin|MockObject $stub */
        $stub = $this->getMockBuilder(Admin::class)
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
        /** @var Admin|MockObject $stub */
        $stub = $this->getMockBuilder(Admin::class)
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
        /** @var Admin|MockObject $stub */
        $stub = $this->getMockBuilder(Admin::class)
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
        /** @var Admin|MockObject $stub */
        $stub = $this->getMockBuilder(Admin::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the findById method.
     *
     * @return void
     */
    public function testFindById(): void
    {
        $admin = Admin::findById(1);

        $this->assertIsObject($admin);
        $this->assertObjectHasAttribute('errors', $admin);
        $this->assertObjectHasAttribute('id', $admin);
        $this->assertObjectHasAttribute('name', $admin);
        $this->assertObjectHasAttribute('email', $admin);
        $this->assertObjectHasAttribute('password_hash', $admin);
        $this->assertObjectHasAttribute('role', $admin);
    }
}
