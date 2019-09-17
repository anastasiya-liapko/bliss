<?php

namespace Tests\App;

use App\DateRule;
use PHPUnit\Framework\TestCase;
use Rakit\Validation\Rule;

/**
 * Class DateRuleTest.
 *
 * @package Tests\App
 */
class DateRuleTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Rule::class, new DateRule());
    }

    /**
     * Tests the check method.
     *
     * @return void
     */
    public function testCheck(): void
    {
        $date_rule = new DateRule();

        $this->assertTrue($date_rule->check('01.01.1970'));
        $this->assertFalse($date_rule->check('01-01-1970'));
    }
}
