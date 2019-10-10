<?php

namespace Tests\App\Models;

use App\Helper;
use App\Models\RememberedClient;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class RememberedClientTest.
 *
 * @package Tests\App\Models
 */
class RememberedClientTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new RememberedClient());
    }

    /**
     * Tests the maybeApplyDiscount method.
     *
     * @return void
     */
    public function testMaybeApplyDiscount(): void
    {
        $this->assertTrue(method_exists(RememberedClient::class, 'maybeApplyDiscount'));
    }

    /**
     * Tests the check method.
     *
     * @return void
     */
    public function testCheck(): void
    {
        $this->assertTrue(method_exists(RememberedClient::class, 'check'));
    }

    /**
     * Tests the incrementWrongInputsNumber method.
     *
     * @return void
     */
    public function testIncrementWrongInputsNumber(): void
    {
        $this->assertTrue(method_exists(RememberedClient::class, 'incrementWrongInputsNumber'));
    }

    /**
     * Tests the checkWrongInputsNumber method.
     *
     * @return void
     */
    public function testCheckWrongInputsNumber(): void
    {
        $this->assertTrue(method_exists(RememberedClient::class, 'checkWrongInputsNumber'));
    }

    /**
     * Tests the incrementTotalWrongInputsNumber method.
     *
     * @return void
     */
    public function testIncrementTotalWrongInputsNumber(): void
    {
        $this->assertTrue(method_exists(RememberedClient::class, 'incrementTotalWrongInputsNumber'));
    }

    /**
     * Tests the checkTotalWrongInputsNumber method.
     *
     * @return void
     */
    public function testCheckTotalWrongInputsNumber(): void
    {
        $this->assertTrue(method_exists(RememberedClient::class, 'checkTotalWrongInputsNumber'));
    }

    /**
     * Tests the resetWrongInputsNumber method.
     *
     * @return void
     */
    public function testResetWrongInputsNumber(): void
    {
        $this->assertTrue(method_exists(RememberedClient::class, 'resetWrongInputsNumber'));
    }

    /**
     * Tests the getSmsCodeExpiresAt method.
     *
     * @return void
     */
    public function testGetSmsCodeExpiresAt(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getSmsCodeExpiresAt());
    }

    /**
     * Tests the getSmsCodeSendsAt method.
     *
     * @return void
     */
    public function testGetSmsCodeSendsAt(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getSmsCodeSendsAt());
    }

    /**
     * Tests the getSmsCode method.
     *
     * @return void
     */
    public function testGetSmsCode(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getSmsCode());
    }

    /**
     * Tests the getTokenExpiresAt method.
     *
     * @return void
     */
    public function testGetTokenExpiresAt(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getTokenExpiresAt());
    }

    /**
     * Tests the getTokenHash method.
     *
     * @return void
     */
    public function testGetTokenHash(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getTokenHash());
    }

    /**
     * Tests the getToken method.
     *
     * @return void
     */
    public function testGetToken(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getToken());
    }

    /**
     * Tests the getSignature method.
     *
     * @return void
     */
    public function testGetSignature(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getSignature());
    }

    /**
     * Tests the getIsTestModeEnabled method.
     *
     * @return void
     */
    public function testGetIsTestModeEnabled(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getIsTestModeEnabled());
    }

    /**
     * Tests the getGoods method.
     *
     * @return void
     */
    public function testGetGoods(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getGoods());
    }

    /**
     * Tests the getIsLoanPostponed method.
     *
     * @return void
     */
    public function testGetIsLoanPostponed(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getIsLoanPostponed());
    }

    /**
     * Tests the getCallbackUrl method.
     *
     * @return void
     */
    public function testGetCallbackUrl(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getCallbackUrl());
    }

    /**
     * Tests the getOrderPrice method.
     *
     * @return void
     */
    public function testGetOrderPrice(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getOrderPrice());
    }

    /**
     * Tests the getOrderId method.
     *
     * @return void
     */
    public function testGetOrderId(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getOrderId());
    }

    /**
     * Tests the getIsVerified method.
     *
     * @return void
     */
    public function testGetIsVerified(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals(0, $stub->getIsVerified());
    }

    /**
     * Tests the getShopId method.
     *
     * @return void
     */
    public function testGetShopId(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getShopId());
    }

    /**
     * Tests the getPhone method.
     *
     * @return void
     */
    public function testGetPhone(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getPhone());
    }

    /**
     * Tests the getErrors method.
     *
     * @return void
     */
    public function testGetErrors(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertEquals([], $stub->getErrors());
    }

    /**
     * Tests the isTokenExpired method.
     *
     * @return void
     * @throws \Exception
     */
    public function testIsTokenExpired(): void
    {
        /** @var RememberedClient|MockObject $stub */
        $stub = $this->getMockBuilder(RememberedClient::class)
                     ->setConstructorArgs([$this->getConstructorData()])
                     ->setMethods(['getTokenExpiresAt'])
                     ->getMock();

        $stub->method('getTokenExpiresAt')
             ->will($this->onConsecutiveCalls(
                 date('Y-m-d H:i:s', time() + 60),
                 date('Y-m-d H:i:s', time() - 60)
             ));

        $this->assertFalse($stub->isTokenExpired());
        $this->assertTrue($stub->isTokenExpired());
    }

    /**
     * Tests the verifyPhone method.
     *
     * @depends testCreate
     *
     * @param RememberedClient $rememberedClient
     *
     * @return void
     * @throws \Exception
     */
    public function testVerifyPhone(RememberedClient $rememberedClient)
    {
        $this->assertTrue($rememberedClient->verifyPhone());
    }

    /**
     * Tests the checkCode method.
     *
     * @return void
     * @throws \Exception
     */
    public function testCheckCode(): void
    {
        $remembered_client = new RememberedClient([
            'shop_id'              => 1,
            'order_id'             => 9,
            'order_price'          => 3000,
            'callback_url'         => 'http://example.ru',
            'is_loan_postponed'    => 0,
            'goods'                => '[{"name":"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 Sony",'
                . '"price":3200,"quantity":1,"is_returnable":1}]',
            'is_test_mode_enabled' => 0,
            'signature'            => 'cf09699817300379694ab3e77670f3a07e01310ce1508885abe5263532c2bdab',
        ]);

        for ($i = 0; $i < 3; $i++) {
            do {
                $sms_code = Helper::generateSmsCode();
            } while ($sms_code === $remembered_client->getSmsCode());

            $this->assertFalse($remembered_client->checkCode($sms_code), 'The code is wrong');
        }

        $this->assertFalse($remembered_client->checkCode($sms_code), 'The wrong code entered more than 3 times');

        for ($i = 0; $i < 7; $i++) {
            $this->assertFalse($remembered_client->checkCode($sms_code), 'The wrong code entered more than 3 times');
        }

        $this->assertFalse($remembered_client->checkCode($sms_code), 'The wrong code entered 10 times');

        $remembered_client = new RememberedClient([
            'shop_id'              => 1,
            'order_id'             => 9,
            'order_price'          => 3000,
            'callback_url'         => 'http://example.ru',
            'is_loan_postponed'    => 0,
            'goods'                => '[{"name":"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 Sony",'
                . '"price":3200,"quantity":1,"is_returnable":1}]',
            'is_test_mode_enabled' => 0,
            'signature'            => 'cf09699817300379694ab3e77670f3a07e01310ce1508885abe5263532c2bdab',
        ]);

        $remembered_client->create();
        $remembered_client->savePhone('+7(999)999-99-99');

        $this->assertTrue($remembered_client->checkCode($remembered_client->getSmsCode()));
    }

    /**
     * Tests the regenerateSmsCode method.
     *
     * @return void
     * @throws \Exception
     */
    public function testRegenerateSmsCode(): void
    {
        $remembered_client = new RememberedClient([
            'shop_id'              => 1,
            'order_id'             => 9,
            'order_price'          => 3000,
            'callback_url'         => 'http://example.ru',
            'is_loan_postponed'    => 0,
            'goods'                => '[{"name":"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 Sony",'
                . '"price":3200,"quantity":1,"is_returnable":1}]',
            'is_test_mode_enabled' => 0,
            'signature'            => 'cf09699817300379694ab3e77670f3a07e01310ce1508885abe5263532c2bdab',
        ]);

        $remembered_client->create();
        $remembered_client->savePhone('+7(909)739-17-54');

        $this->assertFalse($remembered_client->regenerateSmsCode(), 'Not over 3 minutes');
    }

    /**
     * Tests the savePhone method.
     *
     * @return void
     * @throws \Exception
     */
    public function testSavePhone(): void
    {
        /** @var RememberedClient|MockObject $remembered_client */
        $remembered_client = new RememberedClient($this->getConstructorData());

        $this->assertFalse($remembered_client->savePhone('+7(909)739-17-54'));

        /** @var RememberedClient|MockObject $remembered_client */
        $remembered_client = new RememberedClient($this->getConstructorData());

        $remembered_client->create();

        $this->assertTrue($remembered_client->savePhone('+7(909)739-17-54'));
    }

    /**
     * Tests the create method.
     *
     * @return RememberedClient $remembered_client
     * @throws \Exception
     */
    public function testCreate(): RememberedClient
    {
        $remembered_client = new RememberedClient($this->getConstructorData(['goods' => 'a:0:{}']));
        $this->assertFalse($remembered_client->create(), 'Parameter goods is invalid');

        $remembered_client = new RememberedClient($this->getConstructorData([
            'goods'     => '[]',
            'signature' => 'e70d1a79bcb659e1de88fa0abc448bec99e15f94eda471128f73ce3e7d78ae35',
        ]));
        $this->assertFalse($remembered_client->create(), 'Parameter goods must be array');

        $remembered_client = new RememberedClient($this->getConstructorData([
            'goods'     => '[[]]',
            'signature' => '6d1690d3f845e7c6a264737f0c7ca167ea0b544b956ebcf268a8c67e0aa6642f',
        ]));
        $this->assertFalse($remembered_client->create(), 'Parameter goods must be array');

        $remembered_client = new RememberedClient($this->getConstructorData([
            'goods'     => '[{"price":3200,"quantity":1,"is_returnable":1}]',
            'signature' => '9547af8c6624d2e772728be284ca97f3b605a9a0e2dfc304d87a0d33018c9b5f',
        ]));
        $this->assertFalse($remembered_client->create(), 'Required parameter missing — goods["name"]');

        $remembered_client = new RememberedClient($this->getConstructorData([
            'goods'     => '[{"name":"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 Sony","quantity":1,'
                . '"is_returnable":1}]',
            'signature' => '10ab5e0a3668aa1a8b2296a5a1e45133f0c1ccd4d98c83b78a51f75035ee6186',
        ]));
        $this->assertFalse($remembered_client->create(), 'Required parameter missing — goods["price"]');

        $remembered_client = new RememberedClient($this->getConstructorData([
            'goods'     => '[{"name":"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 Sony","price":3200,'
                . '"is_returnable":1}]',
            'signature' => 'aa04c3bc8f129d620265ae4821c48da0c46237528d248d320be1af7a1736b44e',
        ]));
        $this->assertFalse($remembered_client->create(), 'Required parameter missing — goods["quantity"]');

        $remembered_client = new RememberedClient($this->getConstructorData([
            'goods'     =>
                '[{"name":"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 Sony","price":3200,"quantity":1}]',
            'signature' => '1a2c6c0df8727482435e9e8ec4b1139bae19f56222f77954f34e09172c9d96e9',
        ]));
        $this->assertFalse($remembered_client->create(), 'Required parameter missing — goods["is_returnable"]');

        $remembered_client = new RememberedClient($this->getConstructorData([
            'order_price' => 3500,
            'signature'   => '70a02a84bcee2cd05308f64a4264269f7fd5650d016cb7872720b0c9fbcbd6d2',
        ]));
        $this->assertFalse(
            $remembered_client->create(),
            'The total cost of goods does not coincide with the cost of the order.'
        );

        $remembered_client = new RememberedClient($this->getConstructorData(['shop_id' => 9999999]));
        $this->assertFalse($remembered_client->create(), 'Accessing a non-existent store');

        $remembered_client = new RememberedClient($this->getConstructorData(['shop_id' => 2]));
        $this->assertFalse($remembered_client->create(), 'Accessing a deactivated store');

        $remembered_client = new RememberedClient($this->getConstructorData([
            'signature' => '9368d08dc1f6a02e0e2f4cba3e303136cf8b26616d4662faa9969e8ec841503a'
        ]));
        $this->assertFalse($remembered_client->create(), 'Invalid request parameters should not pass');

        $remembered_client = new RememberedClient($this->getConstructorData());
        $this->assertTrue($remembered_client->create());

        return $remembered_client;
    }

    /**
     * Tests the deleteExpiredRecords method.
     *
     * @return void
     * @throws \Exception
     */
    public function testDeleteExpiredRecords(): void
    {
        $this->assertTrue(RememberedClient::deleteExpiredRecords());
    }

    /**
     * Tests the findByToken method.
     *
     * @depends testCreate
     *
     * @param RememberedClient $rememberedClient
     *
     * @return void
     * @throws \Exception
     */
    public function testFindByToken(RememberedClient $rememberedClient): void
    {
        $this->assertFalse(RememberedClient::findByToken(''));

        $remembered_client = RememberedClient::findByToken($rememberedClient->getToken());

        $this->assertIsObject($remembered_client);
        $this->assertObjectHasAttribute('errors', $remembered_client);
        $this->assertObjectHasAttribute('phone', $remembered_client);
        $this->assertObjectHasAttribute('is_verified', $remembered_client);
        $this->assertObjectHasAttribute('shop_id', $remembered_client);
        $this->assertObjectHasAttribute('order_id', $remembered_client);
        $this->assertObjectHasAttribute('order_price', $remembered_client);
        $this->assertObjectHasAttribute('callback_url', $remembered_client);
        $this->assertObjectHasAttribute('is_loan_postponed', $remembered_client);
        $this->assertObjectHasAttribute('goods', $remembered_client);
        $this->assertObjectHasAttribute('is_test_mode_enabled', $remembered_client);
        $this->assertObjectHasAttribute('signature', $remembered_client);
        $this->assertObjectHasAttribute('token', $remembered_client);
        $this->assertObjectHasAttribute('token_hash', $remembered_client);
        $this->assertObjectHasAttribute('token_expires_at', $remembered_client);
        $this->assertObjectHasAttribute('sms_code', $remembered_client);
        $this->assertObjectHasAttribute('sms_code_sends_at', $remembered_client);
        $this->assertObjectHasAttribute('sms_code_expires_at', $remembered_client);
    }

    /**
     * Get the constructor data.
     *
     * @param array $data The data
     *
     * @return array The constructor data.
     */
    private function getConstructorData(array $data = []): array
    {
        return [
            'shop_id'              => array_key_exists('shop_id', $data) ? $data['shop_id'] : 1,
            'order_id'             => array_key_exists('order_id', $data) ? $data['order_id'] : 9,
            'order_price'          => array_key_exists('order_price', $data) ? $data['order_price'] : 3000,
            'callback_url'         =>
                array_key_exists('callback_url', $data) ? $data['callback_url'] : 'http://example.ru',
            'is_loan_postponed'    =>
                array_key_exists('is_loan_postponed', $data) ? $data['is_loan_postponed'] : 0,
            'goods'                => array_key_exists('goods', $data) ? $data['goods'] :
                '[{"name":"\u041d\u0430\u0443\u0448\u043d\u0438\u043a\u0438 Sony","price":3200,"quantity":1,'
                . '"is_returnable":1}]',
            'is_test_mode_enabled' =>
                array_key_exists('is_test_mode_enabled', $data) ? $data['is_test_mode_enabled'] : 0,
            'signature'            => array_key_exists('signature', $data) ? $data['signature'] :
                'cf09699817300379694ab3e77670f3a07e01310ce1508885abe5263532c2bdab',
        ];
    }
}
