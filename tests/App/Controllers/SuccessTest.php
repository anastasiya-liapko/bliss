<?php

namespace Tests\App\Controllers;

use App\Controllers\Success;
use Core\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Success.
 *
 * @package Tests\App\Controllers
 */
class SuccessTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Controller::class, new Success([], new Session(), new Request()));
    }

    /**
     * Tests the getRememberedClientToken method.
     *
     * @return void
     */
    public function testGetRememberedClientToken(): void
    {
        $this->assertTrue(method_exists(Success::class, 'getRememberedClientToken'));
    }

    /**
     * Tests the getConfirmTimerEnd method.
     *
     * @return void
     */
    public function testGetConfirmTimerEnd(): void
    {
        $this->assertTrue(method_exists(Success::class, 'getConfirmTimerEnd'));
    }

    /**
     * Tests the getCallbackUrl method.
     *
     * @return void
     */
    public function testGetCallbackUrl(): void
    {
        $this->assertTrue(method_exists(Success::class, 'getCallbackUrl'));
    }

    /**
     * Tests the validateForm method.
     *
     * @return void
     */
    public function testValidateForm(): void
    {
        $this->assertTrue(method_exists(Success::class, 'validateForm'));
    }

    /**
     * Tests the getCodeAction method.
     *
     * @return void
     */
    public function testGetCodeAction(): void
    {
        $this->assertTrue(method_exists(Success::class, 'getCodeAction'));
    }

    /**
     * Tests the cancelCreditApplicationAction method.
     *
     * @return void
     */
    public function testCancelCreditApplicationAction(): void
    {
        $this->assertTrue(method_exists(Success::class, 'cancelCreditApplicationAction'));
    }

    /**
     * Tests the confirmApplicationAction method.
     *
     * @return void
     */
    public function testConfirmApplicationAction(): void
    {
        $this->assertTrue(method_exists(Success::class, 'confirmApplicationAction'));
    }

    /**
     * Tests the indexAction method.
     *
     * @return void
     */
    public function testIndexAction(): void
    {
        $this->assertTrue(method_exists(Success::class, 'indexAction'));
    }
}
