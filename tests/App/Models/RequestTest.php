<?php

namespace Tests\App\Models;

use App\Models\Request;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestTest.
 *
 * @package Tests\App\Models
 */
class RequestTest extends TestCase
{
    /**
     * Tests class.
     *
     * @throws \Exception
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new Request());
    }

    /**
     * Tests the checkIsRequestExist method.
     *
     * @return void
     */
    public function testCheckIsRequestExist(): void
    {
        $this->assertTrue(method_exists(Request::class, 'checkIsRequestExist'));
    }

    /**
     * Tests the getTimeFinish method.
     *
     * @return void
     */
    public function testGetTimeFinish(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getTimeFinish());
    }

    /**
     * Tests the getTimeStart method.
     *
     * @return void
     */
    public function testGetTimeStart(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getTimeStart());
    }

    /**
     * Tests the getApprovedMfiResponse method.
     *
     * @return void
     */
    public function testGetApprovedMfiResponse(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getApprovedMfiResponse());
    }

    /**
     * Tests the getApprovedMfiId method.
     *
     * @return void
     */
    public function testGetApprovedMfiId(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getApprovedMfiId());
    }

    /**
     * Tests the getStatus method.
     *
     * @return void
     */
    public function testGetStatus(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getStatus());
    }

    /**
     * Tests the getIsLoanPostponed method.
     *
     * @return void
     */
    public function testGetIsLoanPostponed(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getIsLoanPostponed());
    }

    /**
     * Tests the getIsTestModeEnabled method.
     *
     * @return void
     */
    public function testGetIsTestModeEnabled(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(1, $stub->getIsTestModeEnabled());
    }

    /**
     * Tests the getOrderId method.
     *
     * @return void
     */
    public function testGetOrderId(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(9, $stub->getOrderId());
    }

    /**
     * Tests the getShopId method.
     *
     * @return void
     */
    public function testGetShopId(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(1, $stub->getShopId());
    }

    /**
     * Tests the getClientId method.
     *
     * @return void
     */
    public function testGetClientId(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(1, $stub->getClientId());
    }

    /**
     * Tests the getId method.
     *
     * @return void
     */
    public function testGetId(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getId());
    }

    /**
     * Tests the getErrors method.
     *
     * @return void
     */
    public function testGetErrors(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the getCallbackUrlWithParameters method.
     *
     * @return void
     */
    public function testGetCallbackUrlWithParameters(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(['getCallbackUrl', 'getId'])
                     ->getMock();

        $stub->method('getCallbackUrl')
             ->will($this->onConsecutiveCalls('http://example.ru', 'http://example.ru?test=1'));

        $stub->method('getId')
             ->willReturn(1);

        $signature_template = 'order_id=1&request_id=1&status=canceled&is_test_mode_enabled=1'
            . '&secret_key=FMNDesQ58G8y4O8bgGPvsEGFPwEe8Gdj';
        $signature          = hash('sha256', $signature_template);

        $expected = "http://example.ru?order_id=1&request_id=1&status=canceled&is_test_mode_enabled=1"
            . "&signature={$signature}";

        $this->assertEquals($expected, $stub->getCallbackUrlWithParameters(
            'canceled',
            1,
            'FMNDesQ58G8y4O8bgGPvsEGFPwEe8Gdj',
            'http://example.ru',
            0
        ));

        $signature_template = '11canceled1FMNDesQ58G8y4O8bgGPvsEGFPwEe8Gdj';
        $signature          = hash('sha256', $signature_template);

        $expected = "http://example.ru?test=1&order_id=1&request_id=1&status=canceled&is_test_mode_enabled=1"
            . "&signature={$signature}";

        $this->assertEquals($expected, $stub->getCallbackUrlWithParameters(
            'canceled',
            1,
            'FMNDesQ58G8y4O8bgGPvsEGFPwEe8Gdj',
            'http://example.ru?test=1',
            1
        ));
    }

    /**
     * Tests the getTimerEnd method.
     *
     * @param Request $request The request model.
     *
     * @depends testCreate
     *
     * @return void
     */
    public function testGetTimerEnd(Request $request)
    {
        $this->assertEquals(strtotime($request->getTimeStart(), time()) + 300, $request->getTimerEnd(300));
    }

    /**
     * Tests the getStatusName method.
     *
     * @return void
     */
    public function testGetStatusName(): void
    {
        /** @var Request|MockObject $stub */
        $stub = $this->getMockBuilder(Request::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(['getStatus'])
                     ->getMock();

        $stub->method('getStatus')
             ->will($this->onConsecutiveCalls(
                 'pending',
                 'declined',
                 'canceled',
                 'manual',
                 'approved',
                 'confirmed',
                 'waiting_for_limit'
             ));

        $this->assertEquals('В процессе', $stub->getStatusName());
        $this->assertEquals('Отклонена', $stub->getStatusName());
        $this->assertEquals('Отменена клиентом', $stub->getStatusName());
        $this->assertEquals('Требует решение менеджера', $stub->getStatusName());
        $this->assertEquals('Одобрена', $stub->getStatusName());
        $this->assertEquals('Подтверждена клиентом', $stub->getStatusName());
        $this->assertEquals('Ожидает одобрения лимита', $stub->getStatusName());
    }

    /**
     * Tests the getCreditingData method.
     *
     * @param Request $request The request model.
     *
     * @depends testCreate
     *
     * @return void
     * @throws \Exception
     */
    public function testGetCreditingData(Request $request)
    {
        $crediting_data = $request->getCreditingData();

        $this->assertIsArray($crediting_data);
        $this->assertArrayHasKey('request_id', $crediting_data);
        $this->assertArrayHasKey('is_test_mode_enabled', $crediting_data);
        $this->assertArrayHasKey('order_price', $crediting_data);
        $this->assertArrayHasKey('goods', $crediting_data);
        $this->assertArrayHasKey('client_id', $crediting_data);
        $this->assertArrayHasKey('client_last_name', $crediting_data);
        $this->assertArrayHasKey('client_first_name', $crediting_data);
        $this->assertArrayHasKey('client_middle_name', $crediting_data);
        $this->assertArrayHasKey('client_birth_date', $crediting_data);
        $this->assertArrayHasKey('client_birth_place', $crediting_data);
        $this->assertArrayHasKey('client_sex', $crediting_data);
        $this->assertArrayHasKey('client_is_last_name_changed', $crediting_data);
        $this->assertArrayHasKey('client_previous_last_name', $crediting_data);
        $this->assertArrayHasKey('client_tin', $crediting_data);
        $this->assertArrayHasKey('client_snils', $crediting_data);
        $this->assertArrayHasKey('client_passport_number', $crediting_data);
        $this->assertArrayHasKey('client_passport_division_code', $crediting_data);
        $this->assertArrayHasKey('client_passport_issued_by', $crediting_data);
        $this->assertArrayHasKey('client_passport_issued_date', $crediting_data);
        $this->assertArrayHasKey('client_workplace', $crediting_data);
        $this->assertArrayHasKey('client_salary', $crediting_data);
        $this->assertArrayHasKey('client_reg_zip_code', $crediting_data);
        $this->assertArrayHasKey('client_reg_city', $crediting_data);
        $this->assertArrayHasKey('client_reg_street', $crediting_data);
        $this->assertArrayHasKey('client_reg_building', $crediting_data);
        $this->assertArrayHasKey('client_reg_apartment', $crediting_data);
        $this->assertArrayHasKey('client_is_address_matched', $crediting_data);
        $this->assertArrayHasKey('client_fact_zip_code', $crediting_data);
        $this->assertArrayHasKey('client_fact_city', $crediting_data);
        $this->assertArrayHasKey('client_fact_street', $crediting_data);
        $this->assertArrayHasKey('client_fact_building', $crediting_data);
        $this->assertArrayHasKey('client_fact_apartment', $crediting_data);
        $this->assertArrayHasKey('client_email', $crediting_data);
        $this->assertArrayHasKey('client_phone', $crediting_data);
        $this->assertArrayHasKey('client_additional_phone', $crediting_data);
        $this->assertArrayHasKey('client_additional_phone', $crediting_data);
        $this->assertArrayHasKey('sms_code', $crediting_data);
        $this->assertArrayHasKey('callback_url', $crediting_data);
    }

    /**
     * Tests the updateApprovedMfi method.
     *
     * @depends testCreate
     *
     * @param Request $request
     *
     * @return void
     */
    public function testUpdateApprovedMfi(Request $request)
    {
        $this->assertTrue($request->updateApprovedMfi(1, ''));
    }

    /**
     * Tests the updateStatus method.
     *
     * @depends testCreate
     *
     * @param Request $request
     *
     * @return void
     */
    public function testUpdateStatus(Request $request)
    {
        $this->assertTrue($request->updateStatus('approved'));
        $this->assertTrue($request->updateStatus('confirmed'));
    }

    /**
     * Tests the create method.
     *
     * @return Request
     * @throws \Exception
     */
    public function testCreate(): Request
    {
        $request = new Request($this->getConstructorData());
        $this->assertTrue($request->create());

        $request_repeat = new Request($this->getConstructorData());
        $this->assertFalse($request_repeat->create(), 'Attempt to create an existing requisition');

        return $request;
    }

    /**
     * Tests the createOldRequestSignature method.
     *
     * @return void
     */
    public function testCreateOldRequestSignature()
    {
        $shop_id              = 1;
        $order_id             = 1;
        $order_price          = 3000;
        $goods                = '[{"name":"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 Sony","price":3200,'
            . '"quantity":1,"is_returnable":1}]';
        $callback_url         = 'https://example.com';
        $is_loan_postponed    = 1;
        $is_test_mode_enabled = 1;
        $secret_key           = 'FMNDesQ58G8y4O8bgGPvsEGFPwEe8Gdj';

        $expected = hash('sha256', $shop_id . $order_id . $order_price . $callback_url
            . $is_loan_postponed . $goods . $is_test_mode_enabled . $secret_key);

        $this->assertEquals($expected, Request::createOldRequestSignature(
            $shop_id,
            $order_id,
            $order_price,
            $callback_url,
            $is_loan_postponed,
            $goods,
            $is_test_mode_enabled,
            $secret_key
        ));
    }

    /**
     * Tests the createRequestSignature method.
     *
     * @return void
     */
    public function testCreateRequestSignature()
    {
        $shop_id              = 1;
        $order_id             = 1;
        $order_price          = 3000;
        $goods                = '[{"name":"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 Sony","price":3200,'
            . '"quantity":1,"is_returnable":1}]';
        $callback_url         = 'https://example.com';
        $is_loan_postponed    = 1;
        $is_test_mode_enabled = 1;
        $secret_key           = 'FMNDesQ58G8y4O8bgGPvsEGFPwEe8Gdj';

        $expected = hash('sha256', 'shop_id=' . $shop_id
            . '&order_id=' . $order_id
            . '&order_price=' . $order_price
            . '&goods=' . $goods
            . '&callback_url=' . $callback_url
            . '&is_loan_postponed=' . $is_loan_postponed
            . '&is_test_mode_enabled=' . $is_test_mode_enabled
            . '&secret_key=' . $secret_key);

        $this->assertEquals($expected, Request::createRequestSignature(
            $shop_id,
            $order_id,
            $order_price,
            $goods,
            $callback_url,
            $is_loan_postponed,
            $is_test_mode_enabled,
            $secret_key
        ));
    }

    /**
     * Tests the deleteById method.
     *
     * @param Request $request The request model.
     *
     * @depends testCreate
     *
     * @return void
     */
    public function testDeleteById(Request $request)
    {
        $this->assertTrue(Request::deleteById($request->getId()));
    }

    /**
     * Tests the findByOrderIdInShop method.
     *
     * @return void
     * @throws \Exception
     */
    public function testFindByOrderIdInShop(): void
    {
        $request = Request::findByOrderIdInShop(1, 1);

        $this->assertIsObject($request);
        $this->assertObjectHasAttribute('errors', $request);
        $this->assertObjectHasAttribute('id', $request);
        $this->assertObjectHasAttribute('client_id', $request);
        $this->assertObjectHasAttribute('shop_id', $request);
        $this->assertObjectHasAttribute('order_id', $request);
        $this->assertObjectHasAttribute('is_test_mode_enabled', $request);
        $this->assertObjectHasAttribute('is_loan_postponed', $request);
        $this->assertObjectHasAttribute('status', $request);
        $this->assertObjectHasAttribute('approved_mfi_id', $request);
        $this->assertObjectHasAttribute('approved_mfi_response', $request);
        $this->assertObjectHasAttribute('time_start', $request);
        $this->assertObjectHasAttribute('time_finish', $request);
    }

    /**
     * Tests the findByShopIdAndOrderId method.
     *
     * @return void
     * @throws \Exception
     */
    public function testFindByShopIdAndOrderId(): void
    {
        $request = Request::findByShopIdAndOrderId(1, 1);

        $this->assertIsObject($request);
        $this->assertObjectHasAttribute('errors', $request);
        $this->assertObjectHasAttribute('id', $request);
        $this->assertObjectHasAttribute('client_id', $request);
        $this->assertObjectHasAttribute('shop_id', $request);
        $this->assertObjectHasAttribute('order_id', $request);
        $this->assertObjectHasAttribute('is_test_mode_enabled', $request);
        $this->assertObjectHasAttribute('is_loan_postponed', $request);
        $this->assertObjectHasAttribute('status', $request);
        $this->assertObjectHasAttribute('approved_mfi_id', $request);
        $this->assertObjectHasAttribute('approved_mfi_response', $request);
        $this->assertObjectHasAttribute('time_start', $request);
        $this->assertObjectHasAttribute('time_finish', $request);
    }

    /**
     * Tests the findById method.
     *
     * @return void
     * @throws \Exception
     */
    public function testFindById(): void
    {
        $request = Request::findById(1);

        $this->assertIsObject($request);
        $this->assertObjectHasAttribute('errors', $request);
        $this->assertObjectHasAttribute('id', $request);
        $this->assertObjectHasAttribute('client_id', $request);
        $this->assertObjectHasAttribute('shop_id', $request);
        $this->assertObjectHasAttribute('order_id', $request);
        $this->assertObjectHasAttribute('is_test_mode_enabled', $request);
        $this->assertObjectHasAttribute('is_loan_postponed', $request);
        $this->assertObjectHasAttribute('status', $request);
        $this->assertObjectHasAttribute('approved_mfi_id', $request);
        $this->assertObjectHasAttribute('approved_mfi_response', $request);
        $this->assertObjectHasAttribute('time_start', $request);
        $this->assertObjectHasAttribute('time_finish', $request);
    }

    /**
     * Get the constructor data.
     *
     * @return array The constructor data.
     */
    private function getConstructorData(): array
    {
        return [
            'client_id'            => 1,
            'shop_id'              => 1,
            'order_id'             => 9,
            'is_test_mode_enabled' => 1,
            'is_loan_postponed'    => 0,
        ];
    }
}
