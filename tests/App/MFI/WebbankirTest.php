<?php

namespace Tests\App\MFI;

use App\MFI\MFI;
use App\MFI\Webbankir;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class WebbankirTest.
 *
 * @package Tests\App\MFI
 */
class WebbankirTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     * @throws \Exception
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(MFI::class, new Webbankir([
            'is_test_mode_enabled' => 1,
            'client_id'            => 1,
        ], []));
    }

    /**
     * Tests the getNormalizedGoods method.
     *
     * @return void
     */
    public function testGetNormalizedGoods(): void
    {
        $this->assertTrue(method_exists(Webbankir::class, 'getNormalizedGoods'));
    }

    /**
     * Tests the getPassportDivisionName method.
     *
     * @return void
     */
    public function testGetPassportDivisionName(): void
    {
        $this->assertTrue(method_exists(Webbankir::class, 'getPassportDivisionName'));
    }

    /**
     * Tests the getAddress method.
     *
     * @return void
     */
    public function testGetAddress(): void
    {
        $this->assertTrue(method_exists(Webbankir::class, 'getAddress'));
    }

    /**
     * Tests the getTerms method.
     *
     * @return void
     */
    public function testGetTerms(): void
    {
        $this->assertTrue(method_exists(Webbankir::class, 'getTerms'));
    }

    /**
     * Tests the sendCustomerSalaryData method.
     *
     * @return void
     */
    public function testSendCustomerSalaryData(): void
    {
        $this->assertTrue(method_exists(Webbankir::class, 'sendCustomerSalaryData'));
    }

    /**
     * Tests the sendCustomerPassportData method.
     *
     * @return void
     */
    public function testSendCustomerPassportData(): void
    {
        $this->assertTrue(method_exists(Webbankir::class, 'sendCustomerPassportData'));
    }

    /**
     * Tests the confirmCustomer method.
     *
     * @return void
     */
    public function testConfirmCustomer(): void
    {
        $this->assertTrue(method_exists(Webbankir::class, 'confirmCustomer'));
    }

    /**
     * Tests the createCustomer method.
     *
     * @return void
     */
    public function testCreateCustomer(): void
    {
        $this->assertTrue(method_exists(Webbankir::class, 'createCustomer'));
    }

    /**
     * Tests the createLoan method.
     *
     * @return void
     */
    public function testCreateLoan(): void
    {
        $this->assertTrue(method_exists(Webbankir::class, 'createLoan'));
    }

    /**
     * Tests the receiveToken method.
     *
     * @return void
     */
    public function testReceiveToken(): void
    {
        $this->assertTrue(method_exists(Webbankir::class, 'receiveToken'));
    }

    /**
     * Tests the sendConfirmLoanCode method.
     *
     * @return void
     */
    public function testSendConfirmLoanCode(): void
    {
        $this->assertTrue(method_exists(Webbankir::class, 'sendConfirmLoanCode'));
    }

    /**
     * Tests the declineLoanByShop method.
     *
     * @return void
     * @throws \Exception
     */
    public function testDeclineLoanByShop(): void
    {
        $mock = new MockHandler([
            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(404, [], '{"data":{"errors":[{"code":"","message":"","field":null}]}'),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sImV4cCI6MTU1'
                . 'MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAs'
                . 'U","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // DELETE /user/{user_id}/sale
            new Response(
                500,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sImV4cCI6MTU1'
                . 'MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAs'
                . 'U","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // DELETE /user/{user_id}/sale
            new Response(204),
        ]);

        $handler = HandlerStack::create($mock);

        $mock_telegram_bot = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}')
        ]);

        $handler_telegram_bot = HandlerStack::create($mock_telegram_bot);

        $webbankir = new Webbankir($this->getRequestParams(), $this->getApiParameters(), [
            'status'                  => 'issued',
            'customer_id'             => 9778691,
            'contract_id'             => 3,
            'loan_id'                 => 3,
            'loan_body'               => 3000,
            'loan_cost'               => 810,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.0015,
            'loan_terms_link'         => null,
        ], $handler, $handler_telegram_bot);
        $this->assertFalse($webbankir->declineLoanByShop());

        $webbankir = new Webbankir($this->getRequestParams(), $this->getApiParameters(), [
            'status'                  => 'issued',
            'customer_id'             => 9778691,
            'contract_id'             => 3,
            'loan_id'                 => 3,
            'loan_body'               => 3000,
            'loan_cost'               => 810,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.0015,
            'loan_terms_link'         => null,
        ], $handler, $handler_telegram_bot);
        $this->assertFalse($webbankir->declineLoanByShop());

        $webbankir = new Webbankir($this->getRequestParams(), $this->getApiParameters(), [
            'status'                  => 'issued',
            'customer_id'             => 9778691,
            'contract_id'             => 3,
            'loan_id'                 => 3,
            'loan_body'               => 3000,
            'loan_cost'               => 810,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.0015,
            'loan_terms_link'         => null,
        ], $handler, $handler_telegram_bot);
        $result    = $webbankir->declineLoanByShop();
        $this->assertIsArray($result);
        $this->assertEquals('deleted', $result['status']);
    }

    /**
     * Tests the cancelLoanByClient method.
     *
     * @return void
     * @throws \Exception
     */
    public function testCancelLoanByClient(): void
    {
        /** @var Webbankir|MockObject $stub */
        $stub = $this->getMockBuilder(Webbankir::class)
                     ->setConstructorArgs([
                         [
                             'is_test_mode_enabled' => 1,
                             'client_id'            => 1,
                         ],
                         []
                     ])
                     ->setMethods(['declineLoanByShop'])
                     ->getMock();

        $stub->method('declineLoanByShop')
             ->willReturn(true);

        $this->assertTrue($stub->cancelLoanByClient());
    }

    /**
     * Tests the confirmLoanByShop method.
     *
     * @return void
     * @throws \Exception
     */
    public function testConfirmLoanByShop(): void
    {
        $mock = new MockHandler([
            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sImV4cCI6MTU1'
                . 'MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAs'
                . 'U","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // POST /user/{user_id}/sale/{sale_id}/delivery
            new Response(
                500,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sImV4cCI6MTU1'
                . 'MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAs'
                . 'U","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // POST /user/{user_id}/sale/{sale_id}/delivery
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sImV4cCI6MTU1'
                . 'MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAs'
                . 'U","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // POST /user/{user_id}/sale/{sale_id}/delivery
            new Response(
                201,
                [],
                '[{"id":206,"name":"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 \u0432\u043d\u0443\u0442'
                . '\u0440\u0438\u043a\u0430\u043d\u0430\u043b\u044c\u043d\u044b\u0435 Sony MDR-EX15LP Black",'
                . '"price":3000,"operation_type":"loan"}]}}'
            ),
        ]);

        $handler = HandlerStack::create($mock);

        $mock_telegram_bot = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}')
        ]);

        $handler_telegram_bot = HandlerStack::create($mock_telegram_bot);

        $webbankir = new Webbankir($this->getRequestParams(), $this->getApiParameters(), [
            'status'                  => 'issued_postponed',
            'customer_id'             => 9778691,
            'contract_id'             => 3,
            'loan_id'                 => 3,
            'loan_body'               => 3000,
            'loan_cost'               => 810,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.0015,
            'loan_terms_link'         => null,
        ], $handler, $handler_telegram_bot);
        $this->assertFalse($webbankir->confirmLoanByShop(date('Y-m-d\TH:i:sP', time())));

        $webbankir = new Webbankir($this->getRequestParams(), $this->getApiParameters(), [
            'status'                  => 'issued_postponed',
            'customer_id'             => 9778691,
            'contract_id'             => 3,
            'loan_id'                 => 3,
            'loan_body'               => 3000,
            'loan_cost'               => 810,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.0015,
            'loan_terms_link'         => null,
        ], $handler, $handler_telegram_bot);
        $this->assertFalse($webbankir->confirmLoanByShop(date('Y-m-d\TH:i:sP', time())));

        $webbankir = new Webbankir($this->getRequestParams(), $this->getApiParameters(), [
            'status'                  => 'issued_postponed',
            'customer_id'             => 9778691,
            'contract_id'             => 3,
            'loan_id'                 => 3,
            'loan_body'               => 3000,
            'loan_cost'               => 810,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.0015,
            'loan_terms_link'         => null,
        ], $handler, $handler_telegram_bot);
        $result    = $webbankir->confirmLoanByShop(date('Y-m-d\TH:i:sP', time()));
        $this->assertIsArray($result);
        $this->assertEquals('data_not_found', $result['status']);

        $webbankir = new Webbankir($this->getRequestParams(), $this->getApiParameters(), [
            'status'                  => 'issued_postponed',
            'customer_id'             => 9778691,
            'contract_id'             => 3,
            'loan_id'                 => 3,
            'loan_body'               => 3000,
            'loan_cost'               => 810,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.0015,
            'loan_terms_link'         => null,
        ], $handler, $handler_telegram_bot);
        $result    = $webbankir->confirmLoanByShop(date('Y-m-d\TH:i:sP', time()));
        $this->assertIsArray($result);
        $this->assertEquals('issued', $result['status']);
    }

    /**
     * Test the confirmLoanByClient method.
     *
     * @return void
     * @throws \Exception
     */
    public function testConfirmLoanByClient(): void
    {
        $mock = new MockHandler([
            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sImV4cCI6MTU1'
                . 'MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAs'
                . 'U","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // PUT /user/{userId}/sale/{saleId}/code
            new Response(
                500,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sImV4cCI6MTU1'
                . 'MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAs'
                . 'U","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // PUT /user/{userId}/sale/{saleId}/code
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sImV4cCI6MTU1'
                . 'MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAs'
                . 'U","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // PUT /user/{userId}/sale/{saleId}/code
            new Response(
                400,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sImV4cCI6MTU1'
                . 'MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAs'
                . 'U","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // PUT /user/{userId}/sale/{saleId}/code
            new Response(
                200,
                [],
                '{"data":{"id":118,"period":6,"sum":3000,"contract_number":"p7391754\/3","own_funds":0,"products"'
                . ':[{"id":209,"name":"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 \u0432\u043d\u0443\u0442'
                . '\u0440\u0438\u043a\u0430\u043d\u0430\u043b\u044c\u043d\u044b\u0435 Sony MDR-EX15LP Black",'
                . '"price":3000,"operation_type":"loan"}]}}'
            ),
        ]);

        $handler = HandlerStack::create($mock);

        $mock_telegram_bot = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}')
        ]);

        $handler_telegram_bot = HandlerStack::create($mock_telegram_bot);

        $webbankir = new Webbankir($this->getRequestParams(), $this->getApiParameters(), [
            'status'                  => 'approved',
            'customer_id'             => 9778691,
            'contract_id'             => 3,
            'loan_id'                 => 3,
            'loan_body'               => 3000,
            'loan_cost'               => 810,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.0015,
            'loan_terms_link'         => null,
        ], $handler);
        $this->assertFalse($webbankir->confirmLoanByClient('1111'));

        $webbankir = new Webbankir($this->getRequestParams(), $this->getApiParameters(), [
            'status'                  => 'approved',
            'customer_id'             => 9778691,
            'contract_id'             => 3,
            'loan_id'                 => 3,
            'loan_body'               => 3000,
            'loan_cost'               => 810,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.0015,
            'loan_terms_link'         => null,
        ], $handler, $handler_telegram_bot);
        $this->assertFalse($webbankir->confirmLoanByClient('1111'));

        $webbankir = new Webbankir($this->getRequestParams(), $this->getApiParameters(), [
            'status'                  => 'approved',
            'customer_id'             => 9778691,
            'contract_id'             => 3,
            'loan_id'                 => 3,
            'loan_body'               => 3000,
            'loan_cost'               => 810,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.0015,
            'loan_terms_link'         => null,
        ], $handler);
        $result    = $webbankir->confirmLoanByClient('1111');
        $this->assertIsArray($result);
        $this->assertEquals('data_not_found', $result['status']);

        $webbankir = new Webbankir($this->getRequestParams(), $this->getApiParameters(), [
            'status'                  => 'approved',
            'customer_id'             => 9778691,
            'contract_id'             => 3,
            'loan_id'                 => 3,
            'loan_body'               => 3000,
            'loan_cost'               => 810,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.0015,
            'loan_terms_link'         => null,
        ], $handler);
        $result    = $webbankir->confirmLoanByClient('1111');
        $this->assertIsArray($result);
        $this->assertEquals('wrong_sms_code', $result['status']);

        $webbankir = new Webbankir($this->getRequestParams(), $this->getApiParameters(), [
            'status'                  => 'approved',
            'customer_id'             => 9778691,
            'contract_id'             => 3,
            'loan_id'                 => 3,
            'loan_body'               => 3000,
            'loan_cost'               => 810,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.0015,
            'loan_terms_link'         => null,
        ], $handler);
        $result    = $webbankir->confirmLoanByClient('1111');
        $this->assertIsArray($result);
        $this->assertEquals('issued_postponed', $result['status']);
    }

    /**
     * Tests the start method.
     *
     * @return void
     * @throws \Exception
     */
    public function testStart(): void
    {
        $mock = new MockHandler([
            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sImV4cCI6MTU1Mz'
                . 'I5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAsU"'
                . ',"expiration":"2019-03-23T01:21:11+03:00"}}'
            ),
            // GET /user?phone={phone}
            new Response(
                500,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /cashier/{cashierId}/token?password={password}
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sImV4cCI6MT'
                . 'U1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZK'
                . 'AsU","expiration":"2019-03-23T01:21:11+03:00"}}'
            ),
            // GET /user?phone={phone}
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),
            // POST /customer
            new Response(
                400,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /cashier/{cashierId}/token?password={password}
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sIm'
                . 'V4cCI6MTU1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti'
                . '8cOvLZKAsU","expiration":"2019-03-23T01:21:11+03:00"}}'
            ),
            // GET /user?phone={phone}
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),
            // POST /customer
            new Response(
                201,
                [],
                '{"data":{"id":18,"first_name":"\u041f\u0451\u0442\u0440","last_name":"\u041f\u0435\u0442\u0440'
                . '\u043e\u0432","middle_name":"\u041f\u0435\u0442\u0440\u043e\u0432\u0438\u0447","mobile_phone":'
                . '"79097391754"}}'
            ),
            // POST /customer/{customer_id}/code
            new Response(
                400,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /cashier/{cashierId}/token?password={password}
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0s'
                . 'ImV4cCI6MTU1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfB'
                . 'ti8cOvLZKAsU","expiration":"2019-03-23T01:21:11+03:00"}}'
            ),
            // GET /user?phone={phone}
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),
            // POST /customer
            new Response(
                201,
                [],
                '{"data":{"id":18,"first_name":"\u041f\u0451\u0442\u0440","last_name":"\u041f\u0435\u0442\u0440'
                . '\u043e\u0432","middle_name":"\u041f\u0435\u0442\u0440\u043e\u0432\u0438\u0447","mobile_phone":'
                . '"79097391754"}}'
            ),
            // POST /customer/{customer_id}/code
            new Response(204),
            // GET /passport/division_code/{code}
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),
            // GET /address
            new Response(
                400,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /cashier/{cashierId}/token?password={password}
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX0sIm'
                . 'V4cCI6MTU1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti'
                . '8cOvLZKAsU","expiration":"2019-03-23T01:21:11+03:00"}}'
            ),
            // GET /user?phone={phone}
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),
            // POST /customer
            new Response(
                201,
                [],
                '{"data":{"id":18,"first_name":"\u041f\u0451\u0442\u0440","last_name":"\u041f\u0435\u0442\u0440'
                . '\u043e\u0432","middle_name":"\u041f\u0435\u0442\u0440\u043e\u0432\u0438\u0447","mobile_phone":'
                . '"79097391754"}}'
            ),
            // POST /customer/{customer_id}/code
            new Response(204),
            // GET /passport/division_code/{code}
            new Response(
                200,
                [],
                '{"data":{"value":"\u0423\u041f\u0420\u0410\u0412\u041b\u0415\u041d\u0418\u0415 \u0424\u0415'
                . '\u0414\u0415\u0420\u0410\u041b\u042c\u041d\u041e\u0419 \u041c\u0418\u0413\u0420\u0410\u0426'
                . '\u0418\u041e\u041d\u041d\u041e\u0419 \u0421\u041b\u0423\u0416\u0411\u042b \u0420\u041e\u0421\u0421'
                . '\u0418\u0418 \u041f\u041e \u0413\u041e\u0420.\u041c\u041e\u0421\u041a\u0412\u0415"}}'
            ),
            // GET /address?search={searchString}
            new Response(
                200,
                [],
                '{"data":{"suggestions":[{"value":"\u0433 \u041c\u043e\u0441\u043a\u0432\u0430, \u043f\u043e'
                . '\u0441\u0435\u043b\u043e\u043a \u0422\u043e\u043b\u0441\u0442\u043e\u043f\u0430\u043b\u044c\u0446'
                . '\u0435\u0432\u043e, \u0443\u043b \u041b\u0435\u043d\u0438\u043d\u0430, \u0434 10, \u043a\u0432 20",'
                . '"unrestricted_value":"\u0433 \u041c\u043e\u0441\u043a\u0432\u0430, \u0440-\u043d \u0412\u043d\u0443'
                . '\u043a\u043e\u0432\u043e, \u043f\u043e\u0441\u0435\u043b\u043e\u043a \u0422\u043e\u043b\u0441'
                . '\u0442\u043e\u043f\u0430\u043b\u044c\u0446\u0435\u0432\u043e, \u0443\u043b \u041b\u0435\u043d\u0438'
                . '\u043d\u0430, \u0434 10, \u043a\u0432 20","data":{"postal_code":"108809","country":"\u0420\u043e'
                . '\u0441\u0441\u0438\u044f","region_fias_id":"0c5b2444-70a0-4932-980c-b4dc0d3f02b5","region_kladr_id"'
                . ':"7700000000000","region_with_type":"\u0433 \u041c\u043e\u0441\u043a\u0432\u0430","region_type"'
                . ':"\u0433","region_type_full":"\u0433\u043e\u0440\u043e\u0434","region":"\u041c\u043e\u0441\u043a'
                . '\u0432\u0430","area_fias_id":null,"area_kladr_id":null,"area_with_type":null,"area_type":null,'
                . '"area_type_full":null,"area":null,"city_fias_id":"0c5b2444-70a0-4932-980c-b4dc0d3f02b5",'
                . '"city_kladr_id":"7700000000000","city_with_type":"\u0433 \u041c\u043e\u0441\u043a\u0432\u0430",'
                . '"city_type":"\u0433","city_type_full":"\u0433\u043e\u0440\u043e\u0434","city":"\u041c\u043e\u0441'
                . '\u043a\u0432\u0430","city_area":"\u0417\u0430\u043f\u0430\u0434\u043d\u044b\u0439",'
                . '"city_district_fias_id":null,"city_district_kladr_id":null,"city_district_with_type":"\u0440-'
                . '\u043d \u0412\u043d\u0443\u043a\u043e\u0432\u043e","city_district_type":"\u0440-\u043d"'
                . ',"city_district_type_full":"\u0440\u0430\u0439\u043e\u043d","city_district":'
                . '"\u0412\u043d\u0443\u043a\u043e\u0432\u043e","settlement_fias_id":"9e967ebb-9993-433f-94d7-e29ab823f'
                . '359","settlement_kladr_id":"7700000003300","settlement_with_type":"\u043f\u043e\u0441\u0435\u043b'
                . '\u043e\u043a \u0422\u043e\u043b\u0441\u0442\u043e\u043f\u0430\u043b\u044c\u0446\u0435\u0432\u043e",'
                . '"settlement_type":"\u043f","settlement_type_full":"\u043f\u043e\u0441\u0435\u043b\u043e\u043a",'
                . '"settlement":"\u0422\u043e\u043b\u0441\u0442\u043e\u043f\u0430\u043b\u044c\u0446\u0435\u0432\u043e",'
                . '"street_fias_id":"4350977e-e152-4018-a33d-b13143bbecda","street_kladr_id":"77000000033168300",'
                . '"street_with_type":"\u0443\u043b \u041b\u0435\u043d\u0438\u043d\u0430","street_type":"\u0443\u043b",'
                . '"street_type_full":"\u0443\u043b\u0438\u0446\u0430","street":"\u041b\u0435\u043d\u0438\u043d\u0430",'
                . '"house_fias_id":"2e178691-cb96-4579-83ba-414491056a01","house_kladr_id":"7700000003316830012",'
                . '"house_type":"\u0434","house_type_full":"\u0434\u043e\u043c","house":"10","block_type":null,'
                . '"block_type_full":null,"block":null,"flat_type":"\u043a\u0432","flat_type_full":"\u043a'
                . '\u0432\u0430\u0440\u0442\u0438\u0440\u0430","flat":"20","flat_area":null,"square_meter_price":'
                . '"111266","flat_price":null,"postal_box":null,"fias_id":"2e178691-cb96-4579-83ba-414491056a01",'
                . '"fias_code":"77000000033000016830012","fias_level":"8","fias_actuality_state":"0","kladr_id":'
                . '"7700000003316830012","geoname_id":null,"capital_marker":"0","okato":"45268552000","oktmo":'
                . '"45317000","tax_office":"7729","tax_office_legal":"7729","timezone":"UTC+3","geo_lat":"55.6113897",'
                . '"geo_lon":"37.2011968","beltway_hit":"OUT_MKAD","beltway_distance":"16","metro":null,"qc_geo":"0",'
                . '"qc_complete":null,"qc_house":null,"history_values":null,"unparsed_parts":null,"source":null,'
                . '"qc":null}}]}}'
            ),
            // POST /customer/{customer_id}/passport
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /cashier/{cashierId}/token?password={password}
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6MX'
                . '0sImV4cCI6MTU1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57wdoZnIqtz'
                . '8SmfBti8cOvLZKAsU","expiration":"2019-03-23T01:21:11+03:00"}}'
            ),
            // GET /user?phone={phone}
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),
            // POST /customer
            new Response(
                201,
                [],
                '{"data":{"id":18,"first_name":"\u041f\u0451\u0442\u0440","last_name":"\u041f\u0435\u0442\u0440'
                . '\u043e\u0432","middle_name":"\u041f\u0435\u0442\u0440\u043e\u0432\u0438\u0447","mobile_phone":'
                . '"79097391754"}}'
            ),
            // POST /customer/{customer_id}/code
            new Response(204),
            // GET /passport/division_code/{code}
            new Response(
                200,
                [],
                '{"data":{"value":"\u0423\u041f\u0420\u0410\u0412\u041b\u0415\u041d\u0418\u0415 '
                . '\u0424\u0415\u0414\u0415\u0420\u0410\u041b\u042c\u041d\u041e\u0419 \u041c\u0418\u0413'
                . '\u0420\u0410\u0426\u0418\u041e\u041d\u041d\u041e\u0419 \u0421\u041b\u0423\u0416\u0411\u042b '
                . '\u0420\u041e\u0421\u0421\u0418\u0418 \u041f\u041e \u0413\u041e\u0420.\u041c\u041e\u0421\u041a'
                . '\u0412\u0415"}}'
            ),
            // GET /address?search={searchString}
            new Response(
                200,
                [],
                '{"data":{"suggestions":[{"value":"\u0433 \u041c\u043e\u0441\u043a\u0432\u0430, \u043f\u043e'
                . '\u0441\u0435\u043b\u043e\u043a \u0422\u043e\u043b\u0441\u0442\u043e\u043f\u0430\u043b\u044c\u0446'
                . '\u0435\u0432\u043e, \u0443\u043b \u041b\u0435\u043d\u0438\u043d\u0430, \u0434 10, \u043a\u0432 20",'
                . '"unrestricted_value":"\u0433 \u041c\u043e\u0441\u043a\u0432\u0430, \u0440-\u043d \u0412\u043d'
                . '\u0443\u043a\u043e\u0432\u043e, \u043f\u043e\u0441\u0435\u043b\u043e\u043a \u0422\u043e\u043b'
                . '\u0441\u0442\u043e\u043f\u0430\u043b\u044c\u0446\u0435\u0432\u043e, \u0443\u043b \u041b\u0435\u043d'
                . '\u0438\u043d\u0430, \u0434 10, \u043a\u0432 20","data":{"postal_code":"108809","country":"\u0420'
                . '\u043e\u0441\u0441\u0438\u044f","region_fias_id":"0c5b2444-70a0-4932-980c-b4dc0d3f02b5",'
                . '"region_kladr_id":"7700000000000","region_with_type":"\u0433 \u041c\u043e\u0441\u043a\u0432\u0430",'
                . '"region_type":"\u0433","region_type_full":"\u0433\u043e\u0440\u043e\u0434",'
                . '"region":"\u041c\u043e\u0441\u043a\u0432\u0430","area_fias_id":null,"area_kladr_id":null,'
                . '"area_with_type":null,"area_type":null,"area_type_full":null,"area":null,'
                . '"city_fias_id":"0c5b2444-70a0-4932-980c-b4dc0d3f02b5","city_kladr_id":"7700000000000",'
                . '"city_with_type":"\u0433 \u041c\u043e\u0441\u043a\u0432\u0430","city_type":"\u0433",'
                . '"city_type_full":"\u0433\u043e\u0440\u043e\u0434","city":"\u041c\u043e\u0441\u043a\u0432\u0430",'
                . '"city_area":"\u0417\u0430\u043f\u0430\u0434\u043d\u044b\u0439","city_district_fias_id":null,'
                . '"city_district_kladr_id":null,"city_district_with_type":"\u0440-\u043d \u0412\u043d\u0443\u043a'
                . '\u043e\u0432\u043e","city_district_type":"\u0440-\u043d","city_district_type_full":"\u0440\u0430'
                . '\u0439\u043e\u043d","city_district":"\u0412\u043d\u0443\u043a\u043e\u0432\u043e",'
                . '"settlement_fias_id":"9e967ebb-9993-433f-94d7-e29ab823f359","settlement_kladr_id":"7700000003300",'
                . '"settlement_with_type":"\u043f\u043e\u0441\u0435\u043b\u043e\u043a '
                . '\u0422\u043e\u043b\u0441\u0442\u043e\u043f\u0430\u043b\u044c\u0446\u0435\u0432\u043e",'
                . '"settlement_type":"\u043f","settlement_type_full":"\u043f\u043e\u0441\u0435\u043b\u043e\u043a",'
                . '"settlement":"\u0422\u043e\u043b\u0441\u0442\u043e\u043f\u0430\u043b\u044c\u0446\u0435\u0432\u043e",'
                . '"street_fias_id":"4350977e-e152-4018-a33d-b13143bbecda","street_kladr_id":"77000000033168300",'
                . '"street_with_type":"\u0443\u043b \u041b\u0435\u043d\u0438\u043d\u0430","street_type":"\u0443\u043b",'
                . '"street_type_full":"\u0443\u043b\u0438\u0446\u0430","street":"\u041b\u0435\u043d\u0438\u043d\u0430",'
                . '"house_fias_id":"2e178691-cb96-4579-83ba-414491056a01","house_kladr_id":"7700000003316830012",'
                . '"house_type":"\u0434","house_type_full":"\u0434\u043e\u043c","house":"10","block_type":null,'
                . '"block_type_full":null,"block":null,"flat_type":"\u043a\u0432",'
                . '"flat_type_full":"\u043a\u0432\u0430\u0440\u0442\u0438\u0440\u0430","flat":"20","flat_area":null,'
                . '"square_meter_price":"111266","flat_price":null,"postal_box":null,"fias_id":"2e178691-cb96-4579'
                . '-83ba-414491056a01","fias_code":"77000000033000016830012","fias_level":"8","fias_actuality_state":'
                . '"0","kladr_id":"7700000003316830012","geoname_id":null,"capital_marker":"0","okato":"45268552000"'
                . ',"oktmo":"45317000","tax_office":"7729","tax_office_legal":"7729","timezone":"UTC+3","geo_lat":'
                . '"55.6113897","geo_lon":"37.2011968","beltway_hit":"OUT_MKAD","beltway_distance":"16","metro":null,'
                . '"qc_geo":"0","qc_complete":null,"qc_house":null,"history_values":null,"unparsed_parts":null,'
                . '"source":null,"qc":null}}]}}'
            ),
            // POST /customer/{customer_id}/passport
            new Response(204),
            // POST /customer/{customer_id}/beneficial-and-salary
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /cashier/{cashierId}/token?password={password}
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZ'
                . 'CI6MX0sImV4cCI6MTU1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9'
                . '.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAsU","expiration":"2019-03-23T01:21:11+03:00"}}'
            ),
            // GET /user?phone={phone}
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),
            // POST /customer
            new Response(
                201,
                [],
                '{"data":{"id":18,"first_name":"\u041f\u0451\u0442\u0440","last_name":"\u041f\u0435\u0442\u0440'
                . '\u043e\u0432","middle_name":"\u041f\u0435\u0442\u0440\u043e\u0432\u0438\u0447","mobile_phone":'
                . '"79097391754"}}'
            ),
            // POST /customer/{customer_id}/code
            new Response(204),
            // GET /passport/division_code/{code}
            new Response(
                200,
                [],
                '{"data":{"value":"\u0423\u041f\u0420\u0410\u0412\u041b\u0415\u041d\u0418\u0415 \u0424'
                . '\u0415\u0414\u0415\u0420\u0410\u041b\u042c\u041d\u041e\u0419 '
                . '\u041c\u0418\u0413\u0420\u0410\u0426\u0418\u041e\u041d\u041d\u041e\u0419 '
                . '\u0421\u041b\u0423\u0416\u0411\u042b \u0420\u041e\u0421\u0421\u0418\u0418 \u041f\u041e '
                . '\u0413\u041e\u0420.\u041c\u041e\u0421\u041a\u0412\u0415"}}'
            ),
            // GET /address?search={searchString}
            new Response(
                200,
                [],
                '{"data":{"suggestions":[{"value":"\u0433 \u041c\u043e\u0441\u043a\u0432\u0430, '
                . '\u043f\u043e\u0441\u0435\u043b\u043e\u043a \u0422\u043e\u043b\u0441\u0442\u043e'
                . '\u043f\u0430\u043b\u044c\u0446\u0435\u0432\u043e, \u0443\u043b \u041b\u0435\u043d\u0438\u043d\u04'
                . '30, \u0434 10, \u043a\u0432 20","unrestricted_value":"\u0433 \u041c\u043e\u0441\u043a\u0432\u0430, '
                . '\u0440-\u043d \u0412\u043d\u0443\u043a\u043e\u0432\u043e, \u043f\u043e\u0441\u0435\u043b\u043e'
                . '\u043a \u0422\u043e\u043b\u0441\u0442\u043e\u043f\u0430\u043b\u044c\u0446\u0435\u0432\u043e, '
                . '\u0443\u043b \u041b\u0435\u043d\u0438\u043d\u0430, \u0434 10, \u043a\u0432 20","data":'
                . '{"postal_code":"108809","country":"\u0420\u043e\u0441\u0441\u0438\u044f",'
                . '"region_fias_id":"0c5b2444-70a0-4932-980c-b4dc0d3f02b5","region_kladr_id":"7700000000000",'
                . '"region_with_type":"\u0433 \u041c\u043e\u0441\u043a\u0432\u0430","region_type":"\u0433",'
                . '"region_type_full":"\u0433\u043e\u0440\u043e\u0434","region":"\u041c\u043e\u0441\u043a\u0432\u0430",'
                . '"area_fias_id":null,"area_kladr_id":null,"area_with_type":null,"area_type":null,"area_type_full":'
                . 'null,"area":null,"city_fias_id":"0c5b2444-70a0-4932-980c-b4dc0d3f02b5","city_kladr_id":'
                . '"7700000000000","city_with_type":"\u0433 \u041c\u043e\u0441\u043a\u0432\u0430","city_type":'
                . '"\u0433","city_type_full":"\u0433\u043e\u0440\u043e\u0434","city":"\u041c\u043e\u0441\u043a'
                . '\u0432\u0430","city_area":"\u0417\u0430\u043f\u0430\u0434\u043d\u044b\u0439",'
                . '"city_district_fias_id":null,"city_district_kladr_id":null,"city_district_with_type":'
                . '"\u0440-\u043d \u0412\u043d\u0443\u043a\u043e\u0432\u043e","city_district_type":"\u0440-\u043d",'
                . '"city_district_type_full":"\u0440\u0430\u0439\u043e\u043d","city_district":"\u0412\u043d\u0443'
                . '\u043a\u043e\u0432\u043e","settlement_fias_id":"9e967ebb-9993-433f-94d7-e29ab823f359",'
                . '"settlement_kladr_id":"7700000003300","settlement_with_type":"\u043f\u043e\u0441\u0435'
                . '\u043b\u043e\u043a \u0422\u043e\u043b\u0441\u0442\u043e\u043f\u0430\u043b\u044c\u0446'
                . '\u0435\u0432\u043e","settlement_type":"\u043f","settlement_type_full":"\u043f\u043e\u0441'
                . '\u0435\u043b\u043e\u043a","settlement":"\u0422\u043e\u043b\u0441\u0442\u043e\u043f\u0430'
                . '\u043b\u044c\u0446\u0435\u0432\u043e","street_fias_id":"4350977e-e152-4018-a33d-b13143bbecda",'
                . '"street_kladr_id":"77000000033168300","street_with_type":"\u0443\u043b \u041b\u0435\u043d\u0438'
                . '\u043d\u0430","street_type":"\u0443\u043b","street_type_full":"\u0443\u043b\u0438\u0446\u0430",'
                . '"street":"\u041b\u0435\u043d\u0438\u043d\u0430","house_fias_id":"2e178691-cb96-4579-83ba-'
                . '414491056a01","house_kladr_id":"7700000003316830012","house_type":"\u0434","house_type_full":'
                . '"\u0434\u043e\u043c","house":"10","block_type":null,"block_type_full":null,"block":null,'
                . '"flat_type":"\u043a\u0432","flat_type_full":"\u043a\u0432\u0430\u0440\u0442\u0438\u0440\u0430",'
                . '"flat":"20","flat_area":null,"square_meter_price":"111266","flat_price":null,"postal_box":null,'
                . '"fias_id":"2e178691-cb96-4579-83ba-414491056a01","fias_code":"77000000033000016830012","fias_level"'
                . ':"8","fias_actuality_state":"0","kladr_id":"7700000003316830012","geoname_id":null,"capital_marker"'
                . ':"0","okato":"45268552000","oktmo":"45317000","tax_office":"7729","tax_office_legal":"7729",'
                . '"timezone":"UTC+3","geo_lat":"55.6113897","geo_lon":"37.2011968","beltway_hit":"OUT_MKAD",'
                . '"beltway_distance":"16","metro":null,"qc_geo":"0","qc_complete":null,"qc_house":null,'
                . '"history_values":null,"unparsed_parts":null,"source":null,"qc":null}}]}}'
            ),
            // POST /customer/{customer_id}/passport
            new Response(204),
            // POST /customer/{customer_id}/beneficial-and-salary
            new Response(204),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6'
                . 'MX0sImV4cCI6MTU1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9'
                . '.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAsU","expiration":"2019-03-23T01:21:11+03:00"}}'
            ),
            // GET /user?phone={phone}
            new Response(200, [], '{"data":{"id":9778691,"current_limit":15000}}'),
            // POST /user/{userId}/sale
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6M'
                . 'X0sImV4cCI6MTU1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57w'
                . 'doZnIqtz8SmfBti8cOvLZKAsU","expiration":"2019-03-23T01:21:11+03:00"}}'
            ),
            // GET /user?phone={phone}
            new Response(200, [], '{"data":{"id":9778691,"current_limit":15000}}'),
            // POST /user/{userId}/sale
            new Response(
                201,
                [],
                '{"data":{"id":110,"period":6,"sum":3000,"own_funds":0,"products":[{"id":203,"name":'
                . '"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 \u0432\u043d\u0443\u0442\u0440\u0438\u043a'
                . '\u0430\u043d\u0430\u043b\u044c\u043d\u044b\u0435 Sony MDR-EX15LP Black","price":3000,'
                . '"operation_type":"loan"}]}}'
            ),
            // PUT /user/{userId}/sale/{saleId}/code
            new Response(
                500,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InV'
                . 'zZXJJZCI6MX0sImV4cCI6MTU1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvc'
                . 'CJ9.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAsU","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // GET /user?phone={phone}
            new Response(200, [], '{"data":{"id":9778691,"current_limit":15000}}'),
            // POST /user/{userId}/sale
            new Response(
                201,
                [],
                '{"data":{"id":110,"period":6,"sum":3000,"own_funds":0,"products":[{"id":203,"name":'
                . '"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 \u0432\u043d\u0443\u0442\u0440'
                . '\u0438\u043a\u0430\u043d\u0430\u043b\u044c\u043d\u044b\u0435 Sony MDR-EX15LP Black",'
                . '"price":3000,"operation_type":"loan"}]}}'
            ),
            // PUT /user/{userId}/sale/{saleId}/code
            new Response(
                500,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJJZCI6'
                . 'MX0sImV4cCI6MTU1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0alC1Xk7f-57'
                . 'wdoZnIqtz8SmfBti8cOvLZKAsU","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // GET /user?phone={phone}
            new Response(200, [], '{"data":{"id":9778691,"current_limit":15000}}'),
            // POST /user/{userId}/sale
            new Response(
                201,
                [],
                '{"data":{"id":110,"period":6,"sum":3000,"own_funds":0,"products":[{"id":203,"name":'
                . '"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 \u0432\u043d\u0443\u0442\u0440\u0438\u043a\u0430'
                . '\u043d\u0430\u043b\u044c\u043d\u044b\u0435 Sony MDR-EX15LP Black","price":3000,'
                . '"operation_type":"loan"}]}}'
            ),
            // PUT /user/{userId}/sale/{saleId}/code
            new Response(
                404,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJ' .
                'JZCI6MX0sImV4cCI6MTU1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9.zys-l0' .
                'alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAsU","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // GET /user?phone={phone}
            new Response(200, [], '{"data":{"id":9778691,"current_limit":15000}}'),
            // POST /user/{userId}/sale
            new Response(
                201,
                [],
                '{"data":{"id":110,"period":6,"sum":3000,"own_funds":0,"products":[{"id":203,"name":'
                . '"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 \u0432\u043d\u0443\u0442\u0440'
                . '\u0438\u043a\u0430\u043d\u0430\u043b\u044c\u043d\u044b\u0435 Sony MDR-EX15LP Black",'
                . '"price":3000,"operation_type":"loan"}]}}'
            ),
            // PUT /user/{userId}/sale/{saleId}/code
            new Response(201),
            // PATCH /schedule
            new Response(
                500,
                [],
                '{"data":{"errors":[{"code":"","message":"","field":null}]}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZ'
                . 'XJJZCI6MX0sImV4cCI6MTU1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9'
                . '.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAsU","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // GET /user?phone={phone}
            new Response(200, [], '{"data":{"id":9778691,"current_limit":15000}}'),
            // POST /user/{userId}/sale
            new Response(
                201,
                [],
                '{"data":{"id":110,"period":6,"sum":3000,"own_funds":0,"products":[{"id":203,"name":'
                . '"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 \u0432\u043d\u0443'
                . '\u0442\u0440\u0438\u043a\u0430\u043d\u0430\u043b\u044c\u043d\u044b\u0435 Sony MDR-EX15LP Black",'
                . '"price":3000,"operation_type":"loan"}]}}'
            ),
            // PUT /user/{userId}/sale/{saleId}/code
            new Response(201),
            // PATCH /schedule
            new Response(
                200,
                [],
                '{"data":{"loan_body":3000,"daily_percent_rate":0.0015,"amount":3488.01,'
                . '"schedule":[{"date":"2019-04-23T00:00:00+03:00","amount":600,"percents":139.5,"loan_body":460.5},'
                . '{"date":"2019-05-23T00:00:00+03:00","amount":600,"percents":114.3,"loan_body":485.7},'
                . '{"date":"2019-06-23T00:00:00+03:00","amount":600,"percents":95.48,"loan_body":504.52},'
                . '{"date":"2019-07-23T00:00:00+03:00","amount":600,"percents":69.6,"loan_body":530.4},'
                . '{"date":"2019-08-23T00:00:00+03:00","amount":600,"percents":47.43,"loan_body":552.57},'
                . '{"date":"2019-09-23T00:00:00+03:00","amount":488.01,"percents":21.7,"loan_body":466.31}]}}'
            ),

            // receiveToken GET /merchant/{merchantId}/shop/{shopId}/token
            new Response(
                200,
                [],
                '{"data":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJEYXRhIjp7InVzZXJ' .
                'JZCI6MX0sImV4cCI6MTU1MzI5MzI3MSwiaXNzIjoib2F1dGgtZHRlY2gtaW50ZXJuZXQtc2hvcCJ9' .
                '.zys-l0alC1Xk7f-57wdoZnIqtz8SmfBti8cOvLZKAsU","expiration":"'
                . date('Y-m-d\TH:i:sP', time() + 180) . '"}}'
            ),
            // GET /user?phone={phone}
            new Response(204),
        ]);

        $handler = HandlerStack::create($mock);

        $mock_telegram_bot = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}')
        ]);

        $handler_telegram_bot = HandlerStack::create($mock_telegram_bot);

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $this->assertFalse($webbankir->start());

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $this->assertFalse($webbankir->start());

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $this->assertFalse($webbankir->start());

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $this->assertFalse($webbankir->start());

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $this->assertFalse($webbankir->start());

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $this->assertFalse($webbankir->start());

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $this->assertFalse($webbankir->start());

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $result    = $webbankir->start();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('customer_id', $result);
        $this->assertArrayHasKey('contract_id', $result);
        $this->assertArrayHasKey('loan_id', $result);
        $this->assertArrayHasKey('loan_body', $result);
        $this->assertArrayHasKey('loan_cost', $result);
        $this->assertArrayHasKey('loan_period', $result);
        $this->assertArrayHasKey('loan_daily_percent_rate', $result);
        $this->assertArrayHasKey('loan_terms_link', $result);
        $this->assertEquals('waiting_limit', $result['status']);

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $this->assertFalse($webbankir->start());

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $this->assertFalse($webbankir->start());

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $this->assertFalse($webbankir->start());

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $result    = $webbankir->start();
        $this->assertIsArray($result);
        $this->assertEquals('data_not_found', $result['status']);

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $result    = $webbankir->start();
        $this->assertIsArray($result);
        $this->assertEquals('approved', $result['status']);

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $result    = $webbankir->start();
        $this->assertIsArray($result);
        $this->assertEquals('approved', $result['status']);

        $webbankir = new Webbankir(
            $this->getRequestParams(),
            $this->getApiParameters(),
            [],
            $handler,
            $handler_telegram_bot
        );
        $result    = $webbankir->start();
        $this->assertIsArray($result);
        $this->assertEquals('waiting_limit', $result['status']);
    }

    /**
     * Gets request params.
     *
     * @return array
     */
    private function getRequestParams(): array
    {
        return [
            'request_id'                    => 1,
            'is_test_mode_enabled'          => 1,
            'order_price'                   => 3000.00,
            'goods'                         => '[{"name":"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 '
                . '\u0432\u043d\u0443\u0442\u0440\u0438\u043a\u0430\u043d\u0430\u043b\u044c\u043d\u044b\u0435 '
                . 'Sony MDR-EX15LP Black","price":1500,"quantity":2,"is_returnable":1}]',
            'client_id'                     => 1,
            'client_last_name'              => 'Петров',
            'client_first_name'             => 'Пётр',
            'client_middle_name'            => 'Петрович',
            'client_birth_date'             => '1970-01-01 00:00:00',
            'client_birth_place'            => 'г. Москва',
            'client_sex'                    => 'male',
            'client_is_last_name_changed'   => 0,
            'client_previous_last_name'     => '',
            'client_tin'                    => '111111111111',
            'client_snils'                  => '111-111-111 11',
            'client_passport_number'        => '11 11 111111',
            'client_passport_division_code' => '770-001',
            'client_passport_issued_by'     => 'УПРАВЛЕНИЕ ФЕДЕРАЛЬНОЙ МИГРАЦИОННОЙ СЛУЖБЫ РОССИИ ПО ГОР. МОСКВЕ',
            'client_passport_issued_date'   => '2010-01-01 00:00:00',
            'client_workplace'              => 'ООО "Ромашка"',
            'client_salary'                 => 20000,
            'client_reg_zip_code'           => '101000',
            'client_reg_city'               => 'Москва',
            'client_reg_street'             => 'Ленина',
            'client_reg_building'           => '10',
            'client_reg_apartment'          => '20',
            'client_is_address_matched'     => 1,
            'client_fact_zip_code'          => '101000',
            'client_fact_city'              => 'Москва',
            'client_fact_street'            => 'Ленина',
            'client_fact_building'          => '10',
            'client_fact_apartment'         => '20',
            'client_email'                  => 'petrov_pp@mail.ru',
            'client_phone'                  => '79097391754',
            'client_additional_phone'       => '',
            'sms_code'                      => '1234',
            'callback_url'                  => 'https%3A%2F%2Fwww.apple.com%2F',
        ];
    }

    /**
     * Gets api parameters.
     *
     * @return array
     */
    private function getApiParameters(): array
    {
        return [
            'merchantId' => 1,
            'shopId'     => 1,
            'password'   => 'qwerty',
        ];
    }
}
