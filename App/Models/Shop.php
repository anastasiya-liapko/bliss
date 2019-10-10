<?php

namespace App\Models;

use App\Helper;
use App\UniqueRule;
use Core\Model;
use PDO;
use Rakit\Validation\Validator;

/**
 * Class Shop.
 *
 * @package App\Models
 */
class Shop extends Model
{
    /**
     * Error messages.
     *
     * @var array
     */
    private $errors = [];

    /**
     * The ID.
     *
     * @var int|null
     */
    private $id;

    /**
     * The name.
     *
     * @var string|null
     */
    private $name;

    /**
     * The email.
     *
     * @var string|null
     */
    private $email;

    /**
     * Is activated.
     *
     * @var int
     */
    private $is_activated = 0;

    /**
     * The secret key.
     *
     * @var string|null
     */
    private $secret_key;

    /**
     * The organization id.
     *
     * @var int|null
     */
    private $organization_id;

    /**
     * Is the old integration.
     *
     * @var int
     */
    private $is_old_integration = 0;

    /**
     * The Shop constructor.
     *
     * @param array $data (optional) Initial property values.
     *
     * @return void
     * @throws \Exception
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Finds the shop model by the id.
     *
     * @param string $id The id.
     *
     * @return mixed The shop object if found, false otherwise.
     */
    public static function findById(string $id)
    {
        $sql = 'SELECT * FROM shops WHERE id = :id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

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
        $sql = 'DELETE FROM shops WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Creates the account for a shop.
     *
     * @return bool True if success, false otherwise.
     * @throws \Rakit\Validation\RuleQuashException
     */
    public function create(): bool
    {
        $this->validate();

        if (! empty($this->getErrors())) {
            return false;
        }

        $this->secret_key   = Helper::generatePassword(32, false);
        $this->is_activated = 0;

        $sql = 'INSERT INTO shops (name, email, is_activated, secret_key, organization_id) 
                        VALUES (:name, :email, :is_activated, :secret_key, :organization_id)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':is_activated', $this->getIsActivated(), PDO::PARAM_INT);
        $stmt->bindValue(':secret_key', $this->getSecretKey(), PDO::PARAM_STR);
        $stmt->bindValue(':organization_id', $this->getOrganizationId(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->id = $db->lastInsertId();

            return true;
        }

        $this->errors[] = 'Не удалось сохранить данные о магазине, попробуйте ещё раз.'; // @codeCoverageIgnore

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
     * Gets the name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Gets the email.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Gets is the activated.
     *
     * @return int
     */
    public function getIsActivated(): int
    {
        return $this->is_activated;
    }

    /**
     * Gets the secret key.
     *
     * @return string|null
     */
    public function getSecretKey(): ?string
    {
        return $this->secret_key;
    }

    /**
     * Gets the organization id.
     *
     * @return int|null
     */
    public function getOrganizationId(): ?int
    {
        return $this->organization_id;
    }

    /**
     * Gets is the old integration.
     *
     * @return int
     */
    public function getIsOldIntegration(): int
    {
        return $this->is_old_integration;
    }

    /**
     * Validates values.
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
            'email' => $this->email,
        ], [
            'email' => 'unique:shops,email|unique:shops_admins,email',
        ]);

        $validation->setAliases([
            'email' => 'Email',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->errors = $validation->errors()->all();
        }
    }
}
