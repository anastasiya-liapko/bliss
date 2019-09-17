<?php

namespace Tests\App;

use App\Crediting;
use PHPUnit\Framework\TestCase;

/**
 * Class CreditingTest.
 *
 * @package Tests\App
 */
class CreditingTest extends TestCase
{
    /**
     * Tests the hookDeclinedByAllMFI method.
     *
     * @return void
     */
    public function testHookDeclinedByAllMFI(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'hookDeclinedByAllMFI'));
    }

    /**
     * Tests the hookDeclinedByOneMFI method.
     *
     * @return void
     */
    public function testHookDeclinedByOneMFI(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'hookDeclinedByOneMFI'));
    }

    /**
     * Tests the hookApprovedByOneMFI method.
     *
     * @return void
     */
    public function testHookApprovedByOneMFI(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'hookApprovedByOneMFI'));
    }

    /**
     * Tests the hookDidNotHaveTimeForOneMFI method.
     *
     * @return void
     */
    public function testHookDidNotHaveTimeForOneMFI(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'hookDidNotHaveTimeForOneMFI'));
    }

    /**
     * Tests the hookDidNotHaveTime method.
     *
     * @return void
     */
    public function testHookDidNotHaveTime(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'hookDidNotHaveTime'));
    }

    /**
     * Tests the hookDeliverByShop method.
     *
     * @return void
     */
    public function testHookDeliverByShop(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'hookDeliverByShop'));
    }

    /**
     * Tests the hookDeclineByShop method.
     *
     * @return void
     */
    public function testHookDeclineByShop(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'hookDeclineByShop'));
    }

    /**
     * Tests the hookConfirmByShop method.
     *
     * @return void
     */
    public function testHookConfirmByShop(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'hookConfirmByShop'));
    }

    /**
     * Tests the hookCancelByClientUponReceipt method.
     *
     * @return void
     */
    public function testHookCancelByClientUponReceipt(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'hookCancelByClientUponReceipt'));
    }

    /**
     * Tests the hookCancelByClient method.
     *
     * @return void
     */
    public function testHookCancelByClient(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'hookCancelByClient'));
    }

    /**
     * Tests the hookConfirmByClient method.
     *
     * @return void
     */
    public function testSendHookConfirmByClient(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'hookConfirmByClient'));
    }

    /**
     * Tests the maybeSendCallback method.
     *
     * @return void
     */
    public function testMaybeSendCallback(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'maybeSendCallback'));
    }

    /**
     * Tests the sendRememberedClientLink method.
     *
     * @return void
     */
    public function testSendRememberedClientLink(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'sendRememberedClientLink'));
    }

    /**
     * Tests the sendRequest method.
     *
     * @return void
     */
    public function testSendRequest(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'sendRequest'));
    }

    /**
     * Tests the sendRequests method.
     *
     * @return void
     */
    public function testSendRequests(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'sendRequests'));
    }

    /**
     * Tests the getRequestParam method.
     *
     * @return void
     */
    public function testGetRequestParam(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'getRequestParam'));
    }

    /**
     * Tests the startWaitingForLimit method.
     *
     * @return void
     */
    public function testStartWaitingForLimit(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'startWaitingForLimit'));
    }

    /**
     * Tests the startCrediting method.
     *
     * @return void
     */
    public function testStartCrediting(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'startCrediting'));
    }

    /**
     * Tests the deliverByShop method.
     *
     * @return void
     */
    public function testDeliverByShop(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'deliverByShop'));
    }

    /**
     * Tests the declineByShop method.
     *
     * @return void
     */
    public function testDeclineByShop(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'declineByShop'));
    }

    /**
     * Tests the confirmByShop method.
     *
     * @return void
     */
    public function testConfirmByShop(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'confirmByShop'));
    }

    /**
     * Tests the cancelByClientUponReceipt method.
     *
     * @return void
     */
    public function testCancelByClientUponReceipt(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'cancelByClientUponReceipt'));
    }

    /**
     * Tests the cancelByClient method.
     *
     * @return void
     */
    public function testCancelByClient(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'cancelByClient'));
    }

    /**
     * Tests the confirmByClient method.
     *
     * @return void
     */
    public function testConfirmByClient(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'confirmByClient'));
    }

    /**
     * Tests the sendCode method.
     *
     * @return void
     */
    public function testSendCode(): void
    {
        $this->assertTrue(method_exists(Crediting::class, 'sendCode'));
    }
}
