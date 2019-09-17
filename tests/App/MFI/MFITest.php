<?php

namespace Tests\App\MFI;

use PHPUnit\Framework\TestCase;
use App\MFI\MFI;

/**
 * Class MFI.
 *
 * @package Tests\App\MFI
 */
class MFITest extends TestCase
{
    /**
     * The stack.
     *
     * @var MFI
     */
    protected $stack;

    /**
     * Sets up.
     */
    public function setUp(): void
    {
        $this->stack = $this->getMockForAbstractClass('App\MFI\MFI');
    }

    /**
     * Tests the sendAlarmByTelegramBot method.
     *
     * @return void
     */
    public function testSendAlarmByTelegramBot(): void
    {
        $this->assertTrue(method_exists($this->stack, 'sendAlarmByTelegramBot'));
    }

    /**
     * Tests the getLoggingHandlerStack method.
     *
     * @return void
     */
    public function testGetLoggingHandlerStack(): void
    {
        $this->assertTrue(method_exists($this->stack, 'getLoggingHandlerStack'));
    }

    /**
     * Tests the getResponse method.
     *
     * @return void
     */
    public function testGetResponse(): void
    {
        $this->assertTrue(method_exists($this->stack, 'getResponse'));
    }

    /**
     * Tests the getRequestParam method.
     *
     * @return void
     */
    public function testGetRequestParam(): void
    {
        $this->assertTrue(method_exists($this->stack, 'getRequestParam'));
    }

    /**
     * Tests the sendConfirmLoanCode method.
     *
     * @return void
     */
    public function testSendConfirmLoanCode(): void
    {
        $this->assertTrue(method_exists($this->stack, 'sendConfirmLoanCode'));
    }

    /**
     * Tests the declineLoanByShop method.
     *
     * @return void
     */
    public function testDeclineLoanByShop(): void
    {
        $this->assertTrue(method_exists($this->stack, 'declineLoanByShop'));
    }

    /**
     * Tests the cancelLoanByClient method.
     *
     * @return void
     */
    public function testCancelLoanByClient(): void
    {
        $this->assertTrue(method_exists($this->stack, 'cancelLoanByClient'));
    }

    /**
     * Tests the confirmLoanByShop method.
     *
     * @return void
     */
    public function testConfirmLoanByShop(): void
    {
        $this->assertTrue(method_exists($this->stack, 'confirmLoanByShop'));
    }

    /**
     * Tests the confirmLoanByClient method.
     *
     * @return void
     */
    public function testConfirmLoanByClient(): void
    {
        $this->assertTrue(method_exists($this->stack, 'confirmLoanByClient'));
    }

    /**
     * Tests the start method.
     *
     * @return void
     */
    public function testStart(): void
    {
        $this->assertTrue(method_exists($this->stack, 'start'));
    }
}
