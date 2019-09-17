<?php

namespace Tests\App\Models;

use App\Models\Loan;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest.
 *
 * @package Tests\App\Models
 */
class LoanTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new Loan([]));
    }

    /**
     * Tests the getLoanDailyPercentRate method.
     *
     * @return void
     */
    public function testGetLoanDailyPercentRate(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getLoanDailyPercentRate());
    }

    /**
     * Tests the getLoanPeriod method.
     *
     * @return void
     */
    public function testGetLoanPeriod(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getLoanPeriod());
    }

    /**
     * Tests the getLoanCost method.
     *
     * @return void
     */
    public function testGetLoanCost(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getLoanCost());
    }

    /**
     * Tests the getLoanBody method.
     *
     * @return void
     */
    public function testGetLoanBody(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getLoanBody());
    }

    /**
     * Tests the getLoanId method.
     *
     * @return void
     */
    public function testGetLoanId(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getLoanId());
    }

    /**
     * Tests the getContractId method.
     *
     * @return void
     */
    public function testGetContractId(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getContractId());
    }

    /**
     * Tests the getCustomerId method.
     *
     * @return void
     */
    public function testGetCustomerId(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getCustomerId());
    }

    /**
     * Tests the getIsMfiPaid method.
     *
     * @return void
     */
    public function testGetIsMfiPaid(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getIsMfiPaid());
    }

    /**
     * Tests the getStatus method.
     *
     * @return void
     */
    public function testGetStatus(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getStatus());
    }

    /**
     * Tests the getMfiId method.
     *
     * @return void
     */
    public function testGetMfiId(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getMfiId());
    }

    /**
     * Tests the shopId method.
     *
     * @return void
     */
    public function testGetShopId(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getShopId());
    }

    /**
     * Tests the getRequestId method.
     *
     * @return void
     */
    public function testGetRequestId(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getRequestId());
    }

    /**
     * Tests the getId method.
     *
     * @return void
     */
    public function testGetId(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the getErrors method.
     *
     * @return void
     */
    public function testGetErrors(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the getLoan method.
     *
     * @param Loan $loan The loan.
     *
     * @depends testCreate
     *
     * @return void
     */
    public function testGetLoan(Loan $loan)
    {
        $loan_data = $loan->getLoan();

        $this->assertIsArray($loan_data);
        $this->assertArrayHasKey('id', $loan_data);
        $this->assertArrayHasKey('request_id', $loan_data);
        $this->assertArrayHasKey('shop_id', $loan_data);
        $this->assertArrayHasKey('mfi_id', $loan_data);
        $this->assertArrayHasKey('status', $loan_data);
        $this->assertArrayHasKey('is_mfi_paid', $loan_data);
        $this->assertArrayHasKey('customer_id', $loan_data);
        $this->assertArrayHasKey('contract_id', $loan_data);
        $this->assertArrayHasKey('loan_id', $loan_data);
        $this->assertArrayHasKey('loan_body', $loan_data);
        $this->assertArrayHasKey('loan_cost', $loan_data);
        $this->assertArrayHasKey('loan_period', $loan_data);
        $this->assertArrayHasKey('loan_daily_percent_rate', $loan_data);
        $this->assertArrayHasKey('loan_terms_link', $loan_data);
    }

    /**
     * Tests the updateStatus method.
     *
     * @return void
     */
    public function testUpdateStatus(): void
    {
        /** @var Loan|MockObject $stub */
        $stub = $this->getMockBuilder(Loan::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertTrue($stub->updateStatus('issued'));
    }

    /**
     * Tests the create method.
     *
     * @return Loan $loan The loan.
     */
    public function testCreate(): Loan
    {
        $loan = new Loan([
            'request_id'              => 6,
            'shop_id'                 => 1,
            'mfi_id'                  => 1,
            'status'                  => 'pending',
            'customer_id'             => 1,
            'contract_id'             => 1,
            'loan_id'                 => 1,
            'loan_body'               => 3000,
            'loan_cost'               => 450,
            'loan_period'             => 180,
            'loan_daily_percent_rate' => 0.15,
            'loan_terms_link'         => null,
        ]);

        $this->assertTrue($loan->create());

        return $loan;
    }

    /**
     * Tests the deleteById method.
     *
     * @param Loan $loan The loan.
     *
     * @depends testCreate
     *
     * @return void
     */
    public function testDeleteById(Loan $loan): void
    {
        $this->assertTrue(Loan::deleteById($loan->getId()));
    }

    /**
     * Tests the findByRequestId method.
     *
     * @return void
     */
    public function testFindByRequestId(): void
    {
        $loan = Loan::findByRequestId(5);

        $this->assertIsObject($loan);
        $this->assertObjectHasAttribute('id', $loan);
        $this->assertObjectHasAttribute('request_id', $loan);
        $this->assertObjectHasAttribute('shop_id', $loan);
        $this->assertObjectHasAttribute('mfi_id', $loan);
        $this->assertObjectHasAttribute('status', $loan);
        $this->assertObjectHasAttribute('is_mfi_paid', $loan);
        $this->assertObjectHasAttribute('customer_id', $loan);
        $this->assertObjectHasAttribute('contract_id', $loan);
        $this->assertObjectHasAttribute('loan_id', $loan);
        $this->assertObjectHasAttribute('loan_body', $loan);
        $this->assertObjectHasAttribute('loan_cost', $loan);
        $this->assertObjectHasAttribute('loan_period', $loan);
        $this->assertObjectHasAttribute('loan_daily_percent_rate', $loan);
        $this->assertObjectHasAttribute('loan_terms_link', $loan);
    }

    /**
     * Tests the findById method.
     *
     * @return void
     */
    public function testFindById(): void
    {
        $loan = Loan::findById(1);

        $this->assertIsObject($loan);
        $this->assertObjectHasAttribute('id', $loan);
        $this->assertObjectHasAttribute('request_id', $loan);
        $this->assertObjectHasAttribute('shop_id', $loan);
        $this->assertObjectHasAttribute('mfi_id', $loan);
        $this->assertObjectHasAttribute('status', $loan);
        $this->assertObjectHasAttribute('is_mfi_paid', $loan);
        $this->assertObjectHasAttribute('customer_id', $loan);
        $this->assertObjectHasAttribute('contract_id', $loan);
        $this->assertObjectHasAttribute('loan_id', $loan);
        $this->assertObjectHasAttribute('loan_body', $loan);
        $this->assertObjectHasAttribute('loan_cost', $loan);
        $this->assertObjectHasAttribute('loan_period', $loan);
        $this->assertObjectHasAttribute('loan_daily_percent_rate', $loan);
        $this->assertObjectHasAttribute('loan_terms_link', $loan);
    }
}
