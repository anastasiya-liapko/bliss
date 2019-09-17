<?php

namespace App\Models;

use App\Token;
use Core\Model;
use PDO;

/**
 * Class ShopToken.
 *
 * @package App\Models
 */
class ShopToken extends Model
{
    /**
     * Error messages.
     *
     * @var array
     */
    private $errors = [];

    /**
     * The token.
     *
     * @var string|null
     */
    private $token;

    /**
     * The token hash.
     *
     * @var string|null
     */
    private $token_hash;

    /**
     * The token expires at.
     *
     * @var string|null
     */
    private $token_expires_at;

    /**
     * The shop id.
     *
     * @var int|null
     */
    private $shop_id;

    /**
     * OrderToken constructor.
     *
     * @param array $data (optional) Initial property values.
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Finds an model by the token.
     *
     * @param string $token_string The token.
     *
     * @return mixed The object if found, false otherwise.
     * @throws \Exception
     */
    public static function findByToken(string $token_string)
    {
        $token      = new Token($token_string);
        $token_hash = $token->getHash();

        $sql = 'SELECT * FROM shops_tokens WHERE token_hash = :token_hash LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $token_hash, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Deletes expired records.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public static function deleteExpiredRecords(): bool
    {
        $sql = 'DELETE FROM shops_tokens WHERE :now > token_expires_at';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':now', date('Y-m-d H:i:s'), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Creates the order token.
     *
     * @return bool
     * @throws \Exception
     */
    public function create(): bool
    {
        $token                  = new Token();
        $this->token_hash       = $token->getHash();
        $this->token            = $token->getToken();
        $this->token_expires_at = date('Y-m-d H:i:s', time() + 60);

        $sql = 'INSERT INTO shops_tokens (token_hash, shop_id, token_expires_at) 
                VALUES (:token_hash, :shop_id, :token_expires_at)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $this->getTokenHash(), PDO::PARAM_STR);
        $stmt->bindValue(':token_expires_at', $this->getTokenExpiresAt(), PDO::PARAM_STR);
        $stmt->bindValue(':shop_id', $this->getShopId(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        $this->errors[] = 'Не удалось создать токен.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Is the token expired.
     *
     * @return bool True if expired, false otherwise.
     * @throws \Exception
     */
    public function isTokenExpired(): bool
    {
        return time() > strtotime($this->getTokenExpiresAt());
    }

    /**
     * Gets errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Gets the token.
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Gets the token hash.
     *
     * @return string|null
     */
    public function getTokenHash(): ?string
    {
        return $this->token_hash;
    }

    /**
     * Gets the token expiration at.
     *
     * @return string|null
     */
    public function getTokenExpiresAt(): ?string
    {
        return $this->token_expires_at;
    }

    /**
     * Gets the shop id.
     *
     * @return int|null
     */
    public function getShopId(): ?int
    {
        return $this->shop_id;
    }
}
