<?php

namespace Tests\Core;

use Core\Model;
use PHPUnit\Framework\TestCase;
use PDO;

/**
 * Class ModelTest.
 *
 * @package Tests\Core
 */
class ModelTest extends TestCase
{
    /**
     * The stack.
     *
     * @var object
     */
    protected $stack;

    /**
     * Sets up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->stack = new class extends Model {
            /**
             * Gets PDO.
             *
             * @return PDO
             */
            public static function getPDO(): PDO
            {
                return static::getDB();
            }
        };
    }

    /**
     * Tests the getDB method.
     *
     * @return void
     */
    public function testGetDB(): void
    {
        $this->assertIsObject($this->stack::getPDO());
    }
}
