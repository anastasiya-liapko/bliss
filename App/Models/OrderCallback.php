<?php

namespace App\Models;

use App\UniqueRule;
use Core\Model;
use PDO;
use Rakit\Validation\Validator;

/**
 * Class OrderCallback.
 *
 * @package App\Models
 */
class OrderCallback extends Model
{
    /**
     * Error messages.
     *
     * @var array
     */
    private $errors = [];

    /**
     * The id.
     *
     * @var int|null
     */
    private $id;

    /**
     * The order id.
     *
     * @var int|null
     */
    private $order_id;

    /**
     * The callback url.
     *
     * @var string|null
     */
    private $callback_url;

    /**
     * Is the callback sent.
     *
     * @var int
     */
    private $is_callback_sent = 0;

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
     * Finds an model by the order id.
     *
     * @param int $order_id The order id.
     *
     * @return mixed The object if found, false otherwise.
     * @throws \Exception
     */
    public static function findByOrderId(int $order_id)
    {
        $sql = 'SELECT * FROM orders_callbacks WHERE order_id = :order_id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Deletes by id.
     *
     * @param int $id The id.
     *
     * @return bool True if success, false otherwise.
     */
    public static function deleteById(int $id): bool
    {
        $sql = 'DELETE FROM orders_callbacks WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Creates the order callback.
     *
     * @return bool
     * @throws \Exception
     */
    public function create(): bool
    {
        $this->validate();

        if (! empty($this->getErrors())) {
            return false;
        }

        $sql = 'INSERT INTO orders_callbacks (order_id, callback_url, is_callback_sent) 
                VALUES (:order_id, :callback_url, :is_callback_sent)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':order_id', $this->getOrderId(), PDO::PARAM_INT);
        $stmt->bindValue(':callback_url', $this->getCallbackUrl(), PDO::PARAM_STR);
        $stmt->bindValue(':is_callback_sent', $this->getIsCallbackSent(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->id = $db->lastInsertId();

            return true;
        }

        $this->errors[] = 'Не удалось создать коллбэк-ссылку.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Updates is callback sent.
     *
     * @param int $is_callback_sent
     *
     * @return bool
     */
    public function updateIsCallbackSent(int $is_callback_sent): bool
    {
        $this->is_callback_sent = $is_callback_sent ? 1 : 0;

        $sql = 'UPDATE orders_callbacks SET is_callback_sent = :is_callback_sent WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':is_callback_sent', $this->getIsCallbackSent(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        $this->errors[] = 'Не удалось создать коллбэк-ссылку.'; // @codeCoverageIgnore

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
     * Gets the id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * Gets the callback url.
     *
     * @return string|null
     */
    public function getCallbackUrl(): ?string
    {
        return $this->callback_url;
    }

    /**
     * Gets is the callback sent.
     *
     * @return int
     */
    public function getIsCallbackSent(): int
    {
        return $this->is_callback_sent;
    }

    /**
     * Validates on create.
     *
     * @return void
     * @throws \Rakit\Validation\RuleQuashException
     */
    private function validate(): void
    {
        $validator = new Validator;

        $db = static::getDB();

        $validator->addValidator('unique', new UniqueRule($db));

        $validation = $validator->make([
            'order_id' => $this->order_id,
        ], [
            'order_id' => 'unique:orders_callbacks,order_id',
        ]);

        $validation->setAliases([
            'order_id' => 'Идентификатор заказа',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->errors = $validation->errors()->all();
        }
    }
}
