<?php

namespace Tests\App\Models;

use App\Models\DataBaseMigration;
use Core\Model;
use PHPUnit\Framework\TestCase;

/**
 * Class DataBaseMigrationTest.
 *
 * @package Tests\App\Models
 */
class DataBaseMigrationTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     * @throws \Exception
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new DataBaseMigration());
    }

    /**
     * Tests the getNotCompletedMigrations method.
     *
     * @return void
     */
    public function testGetNotCompletedMigrations(): void
    {
        $this->assertIsArray(DataBaseMigration::getNotCompletedMigrations());
    }
}
