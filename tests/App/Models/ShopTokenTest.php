<?php

namespace Tests\App\Models;

use App\Models\ShopToken;
use Core\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ShopTokenTest.
 *
 * @package Tests\App\Models
 */
class ShopTokenTest extends TestCase
{
    /**
     * Tests class.
     *
     * @return void
     * @throws \Exception
     */
    public function testClass(): void
    {
        $this->assertInstanceOf(Model::class, new ShopToken());
    }

    /**
     * Tests the getShopId method.
     *
     * @return void
     */
    public function testGetShopId(): void
    {
        /** @var ShopToken|MockObject $stub */
        $stub = $this->getMockBuilder(ShopToken::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getShopId());
    }

    /**
     * Tests the getTokenExpiresAt method.
     *
     * @return void
     */
    public function testGetTokenExpiresAt(): void
    {
        /** @var ShopToken|MockObject $stub */
        $stub = $this->getMockBuilder(ShopToken::class)
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
        /** @var ShopToken|MockObject $stub */
        $stub = $this->getMockBuilder(ShopToken::class)
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
        /** @var ShopToken|MockObject $stub */
        $stub = $this->getMockBuilder(ShopToken::class)
                     ->setMethods(null)
                     ->getMock();

        $this->assertNull($stub->getToken());
    }

    /**
     * Tests the getErrors method.
     *
     * @return void
     */
    public function testGetErrors(): void
    {
        /** @var ShopToken|MockObject $stub */
        $stub = $this->getMockBuilder(ShopToken::class)
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
        /** @var ShopToken|MockObject $stub */
        $stub = $this->getMockBuilder(ShopToken::class)
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
     * Tests the create method.
     *
     * @return ShopToken $shop_token
     * @throws \Exception
     */
    public function testCreate(): ShopToken
    {
        $shop_token = new ShopToken(['shop_id' => 1]);
        $this->assertTrue($shop_token->create());

        return $shop_token;
    }

    /**
     * Tests the deleteExpiredRecords method.
     *
     * @return void
     * @throws \Exception
     */
    public function testDeleteExpiredRecords(): void
    {
        $this->assertTrue(ShopToken::deleteExpiredRecords());
    }

    /**
     * Tests the findByToken method.
     *
     * @depends testCreate
     *
     * @param ShopToken $shop_token
     *
     * @return void
     * @throws \Exception
     */
    public function testFindByToken(ShopToken $shop_token): void
    {
        $this->assertFalse(ShopToken::findByToken(''));

        $shop_token = ShopToken::findByToken($shop_token->getToken());

        $this->assertIsObject($shop_token);
        $this->assertObjectHasAttribute('errors', $shop_token);
        $this->assertObjectHasAttribute('token', $shop_token);
        $this->assertObjectHasAttribute('token_hash', $shop_token);
        $this->assertObjectHasAttribute('token_expires_at', $shop_token);
        $this->assertObjectHasAttribute('shop_id', $shop_token);
    }
}
