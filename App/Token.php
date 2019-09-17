<?php

namespace App;

/**
 * Class Token.
 *
 * @package App
 */
class Token
{
    /**
     * The token value.
     *
     * @var string
     */
    protected $token;

    /**
     * Token constructor.
     *
     * Creates a new random token.
     *
     * @param string $value (optional) A token value.
     *
     * @throws \Exception
     */
    public function __construct($value = null)
    {
        if ($value) {
            $this->token = $value;
        } else {
            $this->token = bin2hex(random_bytes(16));
        }
    }

    /**
     * Gets token.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Gets the hashed token value.
     *
     * @return string
     */
    public function getHash(): string
    {
        return hash_hmac('sha256', $this->token, Config::SECRET_KEY);
    }
}
