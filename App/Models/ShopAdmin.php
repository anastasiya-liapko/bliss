<?php

namespace App\Models;

use App\Helper;
use App\UniqueRule;
use Core\Model;
use PDO;
use Rakit\Validation\Validator;

/**
 * Class ShopAdmin.
 *
 * @package App\Models
 */
class ShopAdmin extends Model
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
     * The password.
     *
     * @var string|null
     */
    private $password;

    /**
     * The password hash.
     *
     * @var string|null
     */
    private $password_hash;

    /**
     * The phone.
     *
     * @var string|null
     */
    private $phone;

    /**
     * The role.
     *
     * @var string|null
     */
    private $role;

    /**
     * The shop id.
     *
     * @var int|null
     */
    private $shop_id;

    /**
     * Is activated.
     *
     * @var int
     */
    private $is_activated = 0;

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
     * Finds an admin model by ID.
     *
     * @param string $id The admin ID.
     *
     * @return mixed The admin object if found, false otherwise.
     */
    public static function findById(string $id)
    {
        $sql = 'SELECT * FROM shops_admins WHERE id = :id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Deletes by the id.
     *
     * @param int $id The id.
     *
     * @return bool True if success, false otherwise.
     */
    public static function deleteById(int $id): bool
    {
        $sql = 'DELETE FROM shops_admins WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Creates the admin for a shop
     *
     * @return bool True if success, false otherwise.
     * @throws \Rakit\Validation\RuleQuashException
     */
    public function create(): bool
    {
        $this->validate();

        if (! empty($this->errors)) {
            return false;
        }

        $this->password      = Helper::generatePassword();
        $this->password_hash = md5($this->getPassword());
        $this->phone         = preg_replace('/[-)(\s]/', '', $this->getPhone());

        $sql = 'INSERT INTO shops_admins (name, email, password_hash, phone, role, shop_id, is_activated) 
                        VALUES (:name, :email, :password_hash, :phone, :role, :shop_id, :is_activated)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':password_hash', $this->getPasswordHash(), PDO::PARAM_STR);
        $stmt->bindValue(':phone', $this->getPhone(), PDO::PARAM_STR);
        $stmt->bindValue(':role', $this->getRole(), PDO::PARAM_STR);
        $stmt->bindValue(':shop_id', $this->getShopId(), PDO::PARAM_INT);
        $stmt->bindValue(':is_activated', $this->getIsActivated(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->id = $db->lastInsertId();

            return true;
        }

        $this->errors[] = 'Не удалось сохранить данные, попробуйте ещё раз.'; // @codeCoverageIgnore

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
     * Gets the password.
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
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
     * Gets the phone.
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
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

    /**
     * Gets the shop id.
     *
     * @return int|null
     */
    public function getShopId(): ?int
    {
        return $this->shop_id;
    }

    /**
     * Gets is activated.
     *
     * @return int
     */
    public function getIsActivated(): int
    {
        return $this->is_activated;
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
            'email' => 'unique:shops_admins,email',
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
