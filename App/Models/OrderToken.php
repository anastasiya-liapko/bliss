<?php

namespace App\Models;

use App\SiteInfo;
use App\Token;
use Core\Model;
use PDO;

/**
 * Class OrderToken.
 *
 * @package App\Models
 */
class OrderToken extends Model
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
     * The order id.
     *
     * @var int|null
     */
    private $order_id;

    /**
     * The client phone.
     *
     * @var string|null
     */
    private $client_phone;

    /**
     * The process order link.
     *
     * @var string|null
     */
    private $process_order_link;

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
     * Creates the order token.
     *
     * @return bool
     * @throws \Exception
     */
    public function create(): bool
    {
        $token                    = new Token();
        $this->token_hash         = $token->getHash();
        $this->token              = $token->getToken();
        $this->client_phone       = preg_replace('/[-)+(\s]/', '', $this->getClientPhone());
        $this->process_order_link = SiteInfo::getSchemeAndHttpHost() . '/process-order?token=' . $this->getToken();

        $sql = 'INSERT INTO orders_tokens (token_hash, order_id, client_phone, process_order_link) 
                VALUES (:token_hash, :order_id, :client_phone, :process_order_link)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $this->getTokenHash(), PDO::PARAM_STR);
        $stmt->bindValue(':order_id', $this->getOrderId(), PDO::PARAM_INT);
        $stmt->bindValue(':client_phone', $this->getClientPhone(), PDO::PARAM_STR);
        $stmt->bindValue(':process_order_link', $this->getProcessOrderLink(), PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        }

        $this->errors[] = 'Не удалось создать токен заказа.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
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
     * Gets the order id.
     *
     * @return int|null
     */
    public function getOrderId(): ?int
    {
        return $this->order_id;
    }

    /**
     * Gets the client phone.
     *
     * @return string|null
     */
    public function getClientPhone(): ?string
    {
        return $this->client_phone;
    }

    /**
     * Gets the process order link.
     *
     * @return string|null
     */
    public function getProcessOrderLink(): ?string
    {
        return $this->process_order_link;
    }
}
