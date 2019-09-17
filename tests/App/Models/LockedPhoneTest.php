<?php

namespace Tests\App\Models;

use App\Models\LockedPhone;
use Core\Model;
use PHPUnit\Framework\TestCase;

/**
 * Class LockedPhoneTest.
 *
 * @package Tests\App\Models
 */
class LockedPhoneTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new LockedPhone());
    }

    /**
     * Tests the deleteExpiredRecords method.
     *
     * @return void
     * @throws \Exception
     */
    public function testDeleteExpiredRecords(): void
    {
        $this->assertTrue(LockedPhone::deleteExpiredRecords());
    }

    /**
     * Tests the isLocked method.
     *
     * @depends testLock
     *
     * @return void
     * @throws \Exception
     */
    public function testIsLocked(): void
    {
        $this->assertTrue(LockedPhone::isLocked('71111111111'));
    }

    /**
     * Tests the lock method.
     *
     * @return void
     * @throws \Exception
     */
    public function testLock(): void
    {
        $this->assertTrue(LockedPhone::lock('71111111111'));
    }
}
