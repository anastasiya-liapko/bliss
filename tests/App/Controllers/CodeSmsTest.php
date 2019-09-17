<?php

namespace Tests\App\Controllers;

use App\Controllers\CodeSms;
use Core\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class CodeSmsTest.
 *
 * @package Tests\App\Controllers
 */
class CodeSmsTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Controller::class, new CodeSms([], new Session(), new Request()));
    }

    /**
     * Tests the getRememberedClientToken method.
     *
     * @return void
     */
    public function testGetRememberedClientToken(): void
    {
        $this->assertTrue(method_exists(CodeSms::class, 'getRememberedClientToken'));
    }

    /**
     * Tests the getConfirmTimerEnd method.
     *
     * @return void
     */
    public function testGetConfirmTimerEnd(): void
    {
        $this->assertTrue(method_exists(CodeSms::class, 'getConfirmTimerEnd'));
    }

    /**
     * Tests the maybeSendAboutClientConfirmedPhone method.
     *
     * @return void
     */
    public function testMaybeSendAboutClientConfirmedPhone(): void
    {
        $this->assertTrue(method_exists(CodeSms::class, 'maybeSendAboutClientConfirmedPhone'));
    }

    /**
     * Tests the getCodeAction method.
     *
     * @return void
     */
    public function testGetCodeAction(): void
    {
        $this->assertTrue(method_exists(CodeSms::class, 'getCodeAction'));
    }

    /**
     * Tests the checkCodeAction method.
     *
     * @return void
     */
    public function testCheckCodeAction(): void
    {
        $this->assertTrue(method_exists(CodeSms::class, 'checkCodeAction'));
    }

    /**
     * Tests the indexAction method.
     *
     * @return void
     */
    public function testIndexAction(): void
    {
        $this->assertTrue(method_exists(CodeSms::class, 'indexAction'));
    }
}
