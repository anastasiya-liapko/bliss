<?php

namespace Tests\App;

use App\PlainRule;
use PHPUnit\Framework\TestCase;
use Rakit\Validation\Rule;

/**
 * Class PlainRuleTest.
 *
 * @package Tests\App
 */
class PlainRuleTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Rule::class, new PlainRule());
    }

    /**
     * Tests the check method.
     *
     * @return void
     */
    public function testCheck(): void
    {
        $plain_rule = new PlainRule();

        $this->assertTrue($plain_rule->check('text'));
        $this->assertFalse($plain_rule->check('<b>text</b>'));
    }
}
