<?php

namespace Tests\App;

use App\UniqueRule;
use Core\Model;
use PHPUnit\Framework\TestCase;
use Rakit\Validation\Rule;
use PDO;

/**
 * Class UniqueRuleTest.
 *
 * @package Tests\App
 */
class UniqueRuleTest extends TestCase
{
    /**
     * The stack.
     *
     * @var Model
     */
    protected $stack;

    /**
     * Sets up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->stack = new class extends Model
        {
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
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Rule::class, new UniqueRule($this->stack::getPDO()));
    }

    /**
     * Tests the check method.
     *
     * @return void
     */
    public function testCheck(): void
    {
        $unique_rule = new UniqueRule($this->stack::getPDO());

        $unique_rule->setParameter('table', 'clients');
        $unique_rule->setParameter('column', 'email');

        $this->assertTrue($unique_rule->check('sidorov_ss@mail.ru'));
        $this->assertFalse($unique_rule->check('petrov_pp@mail.ru'));
    }
}
