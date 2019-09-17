<?php

namespace App\Models;

use Core\Model;
use PDO;

/**
 * Class Admin.
 *
 * @package App\Models
 */
class Admin extends Model
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
     * The password_hash.
     *
     * @var string|null
     */
    private $password_hash;

    /**
     * The role.
     *
     * @var string|null
     */
    private $role;

    /**
     * The ShopAdmin constructor.
     *
     * @param array $data (optional) Initial property values.
     *
     * @throws \Exception
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Finds an admin model by the id.
     *
     * @param int $id The id.
     *
     * @return mixed The admin object if found, false otherwise.
     */
    public static function findById(int $id)
    {
        $sql = 'SELECT * FROM admins WHERE id = :id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
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
     * Gets the password hash.
     *
     * @return string|null
     */
    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    /**
     * Gets the role.
     *
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }
}
