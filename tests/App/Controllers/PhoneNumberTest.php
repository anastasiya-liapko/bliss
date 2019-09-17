<?php

namespace Tests\App\Controllers;

use App\Controllers\PhoneNumber;
use Core\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class PhoneNumberTest.
 *
 * @package Tests\App\Controllers
 */
class PhoneNumberTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Controller::class, new PhoneNumber([], new Session(), new Request()));
    }

    /**
     * Tests the validateForm method.
     *
     * @return void
     */
    public function testValidateForm(): void
    {
        $this->assertTrue(method_exists(PhoneNumber::class, 'validateForm'));
    }

    /**
     * Tests the validateRequest method.
     *
     * @return void
     */
    public function testValidateRequest(): void
    {
        $this->assertTrue(method_exists(PhoneNumber::class, 'validateRequest'));
    }

    /**
     * Tests the getRememberedClientToken method.
     *
     * @return void
     */
    public function testGetRememberedClientToken(): void
    {
        $this->assertTrue(method_exists(PhoneNumber::class, 'getRememberedClientToken'));
    }

    /**
     * Tests the logUserRequest method.
     *
     * @return void
     */
    public function testLogUserRequest(): void
    {
        $this->assertTrue(method_exists(PhoneNumber::class, 'logUserRequest'));
    }

    /**
     * Tests the maybeSaveHttpRefererInSession method.
     *
     * @return void
     */
    public function testMaybeSaveHttpRefererInSession(): void
    {
        $this->assertTrue(method_exists(PhoneNumber::class, 'maybeSaveHttpRefererInSession'));
    }

    /**
     * Tests the getCodeAction method.
     *
     * @return void
     */
    public function testGetCodeAction(): void
    {
        $this->assertTrue(method_exists(PhoneNumber::class, 'getCodeAction'));
    }

    /**
     * Tests the indexAction method.
     *
     * @return void
     */
    public function testIndexAction(): void
    {
        $this->assertTrue(method_exists(PhoneNumber::class, 'indexAction'));
    }
}
