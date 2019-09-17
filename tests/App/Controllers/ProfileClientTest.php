<?php

namespace Tests\App\Controllers;

use App\Controllers\ProfileClient;
use Core\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ProfileClientTest.
 *
 * @package Tests\App\Controllers
 */
class ProfileClientTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Controller::class, new ProfileClient([], new Session(), new Request()));
    }

    /**
     * Tests the maybeSendAboutClientWaitingForLimit method.
     *
     * @return void
     */
    public function testMaybeSendAboutClientWaitingForLimit(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'maybeSendAboutClientWaitingForLimit'));
    }

    /**
     * Tests the maybeSendAboutClientCreatedRequest method.
     *
     * @return void
     */
    public function testMaybeSendAboutClientCreatedRequest(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'maybeSendAboutClientCreatedRequest'));
    }

    /**
     * Tests the validateForm method.
     *
     * @return void
     */
    public function testValidateForm(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'validateForm'));
    }

    /**
     * Tests the getRememberedClientToken method.
     *
     * @return void
     */
    public function testGetRememberedClientToken(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'getRememberedClientToken'));
    }

    /**
     * Tests the getCallbackUrl method.
     *
     * @return void
     */
    public function testGetCallbackUrl(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'getCallbackUrl'));
    }

    /**
     * Tests the getTimerEnd method.
     *
     * @return void
     */
    public function testGetTimerEnd(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'getTimerEnd'));
    }

    /**
     * Tests the getRequestMaxTime method.
     *
     * @return void
     */
    public function testGetRequestMaxTime(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'getRequestMaxTime'));
    }

    /**
     * Tests the startWaitingForLimit method.
     *
     * @return void
     */
    public function testStartWaitingForLimit(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'startWaitingForLimit'));
    }

    /**
     * Tests the startCrediting method.
     *
     * @return void
     */
    public function testStartCrediting(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'startCrediting'));
    }

    /**
     * Tests the createRequest method.
     *
     * @return void
     */
    public function testCreateRequest(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'createRequest'));
    }

    /**
     * Tests the maybeCreateOrder method.
     *
     * @return void
     */
    public function testMaybeCreateOrder(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'maybeCreateOrder'));
    }

    /**
     * Tests the maybeCreateClient method.
     *
     * @return void
     */
    public function testMaybeCreateClient(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'maybeCreateClient'));
    }

    /**
     * Tests the waitResponseAction method.
     *
     * @return void
     */
    public function testWaitResponseAction(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'waitResponseAction'));
    }

    /**
     * Tests the cancelRequestAction method.
     *
     * @return void
     */
    public function testCancelRequestAction(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'cancelRequestAction'));
    }

    /**
     * Tests the checkRequestAction method.
     *
     * @return void
     */
    public function testCheckRequestAction(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'checkRequestAction'));
    }

    /**
     * Tests the createRequestAction method.
     *
     * @return void
     */
    public function testCreateRequestAction(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'createRequestAction'));
    }

    /**
     * Tests the indexAction method.
     *
     * @return void
     */
    public function testIndexAction(): void
    {
        $this->assertTrue(method_exists(ProfileClient::class, 'indexAction'));
    }
}
