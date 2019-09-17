<?php

namespace Tests\App;

use App\Config;
use App\Token;
use PHPUnit\Framework\TestCase;

/**
 * Class TelegramBotTest.
 *
 * @package Tests\App
 */
class TokenTest extends TestCase
{
    /**
     * Tests the getHash method.
     *
     * @param Token $token The token.
     *
     * @depends testGetToken
     *
     * @return void
     */
    public function testGetHash(Token $token): void
    {
        $this->assertEquals($token->getHash(), hash_hmac('sha256', $token->getToken(), Config::SECRET_KEY));
    }

    /**
     * Tests the getToken method.
     *
     * @return Token $token The token.
     * @throws \Exception
     */
    public function testGetToken(): Token
    {
        $token = new Token();
        $this->assertIsString($token->getToken());

        return $token;
    }
}
