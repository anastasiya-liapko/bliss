<?php

namespace Tests\App\Models;

use App\Models\MFI;
use Core\Model;
use PHPUnit\Framework\TestCase;

/**
 * Class MFITest.
 *
 * @package Tests\App\Models
 */
class MFITest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new MFI());
    }

    /**
     * Tests the getMFITerms method.
     *
     * @return void
     */
    public function testGetMFITerms(): void
    {
        $this->assertIsArray(MFI::getMFITerms(1));
        $this->assertEmpty(MFI::getMFITerms(9999999));
    }

    /**
     * Tests the getRequestMaxTime method.
     *
     * @return void
     */
    public function testGetRequestMaxTime(): void
    {
        $this->assertEquals(600, MFI::getRequestMaxTime(1, 3000, 1));
        $this->assertEquals(180, MFI::getRequestMaxTime(1, 3000, 0));
        $this->assertEquals(180, MFI::getRequestMaxTime(1, 2000, 0));
        $this->assertEquals(180, MFI::getRequestMaxTime(1, 16000, 0));
    }

    /**
     * Tests the updateResponse method.
     *
     * @return void
     */
    public function testUpdateResponse(): void
    {
        $this->assertTrue(MFI::updateResponse(1, 3, 'declined'));
        $this->assertTrue(MFI::updateResponse(1, 3, 'did_not_have_time'));
    }

    /**
     * Tests the createResponse method.
     *
     * @return void
     */
    public function testCreateResponse(): void
    {
        $this->assertTrue(MFI::createResponse(1, 7, 'approved'));
    }

    /**
     * Tests the getDidNotAnswered method.
     *
     * @return void
     */
    public function testGetDidNotAnswered(): void
    {
        $mfi_list = MFI::getDidNotAnswered(3);
        $this->assertIsArray($mfi_list);
        $this->assertCount(1, $mfi_list);
        $this->assertArrayHasKey('id', $mfi_list[0]);
        $this->assertArrayHasKey('name', $mfi_list[0]);
        $this->assertArrayHasKey('phone', $mfi_list[0]);
        $this->assertArrayHasKey('email', $mfi_list[0]);
        $this->assertArrayHasKey('time_limit', $mfi_list[0]);
        $this->assertArrayHasKey('slug', $mfi_list[0]);
        $this->assertArrayHasKey('mfi_api_parameters', $mfi_list[0]);

        $mfi_list = MFI::getDidNotAnswered(1);
        $this->assertEmpty($mfi_list);
    }

    /**
     * Tests the getResponse method.
     *
     * @return void
     */
    public function testGetResponses(): void
    {
        $response = MFI::getResponses(1, 'declined');

        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('mfi_id', $response[0]);
        $this->assertArrayHasKey('request_id', $response[0]);
        $this->assertArrayHasKey('status', $response[0]);
        $this->assertArrayHasKey('time_response', $response[0]);
    }

    /**
     * Tests the getForShop method.
     *
     * @return void
     */
    public function testGetForShop(): void
    {
        $mfi_data = MFI::getForShop(1, 3000, 1);

        $this->assertIsArray($mfi_data);
        $this->assertArrayHasKey('id', $mfi_data[0]);
        $this->assertArrayHasKey('name', $mfi_data[0]);
        $this->assertArrayHasKey('phone', $mfi_data[0]);
        $this->assertArrayHasKey('email', $mfi_data[0]);
        $this->assertArrayHasKey('time_limit', $mfi_data[0]);
        $this->assertArrayHasKey('slug', $mfi_data[0]);
        $this->assertArrayHasKey('mfi_api_parameters', $mfi_data[0]);
    }

    /**
     * Tests the getApiParametersForShop method.
     *
     * @return void
     */
    public function testGetApiParametersForShop(): void
    {
        $this->assertIsArray(MFI::getApiParametersForShop(1, 1));
    }

    /**
     * Tests the getById method.
     *
     * @return void
     */
    public function testGetById(): void
    {
        $mfi = MFI::getById(1);

        $this->assertIsArray($mfi);
        $this->assertArrayHasKey('id', $mfi);
        $this->assertArrayHasKey('name', $mfi);
        $this->assertArrayHasKey('phone', $mfi);
        $this->assertArrayHasKey('email', $mfi);
        $this->assertArrayHasKey('min_loan_sum', $mfi);
        $this->assertArrayHasKey('max_loan_sum', $mfi);
        $this->assertArrayHasKey('can_loan_postponed', $mfi);
        $this->assertArrayHasKey('time_limit', $mfi);
        $this->assertArrayHasKey('slug', $mfi);
    }
}
