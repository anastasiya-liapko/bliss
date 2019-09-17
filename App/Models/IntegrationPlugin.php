<?php

namespace App\Models;

use Core\Model;
use PDO;

/**
 * Class IntegrationPlugin.
 *
 * @package App\Models
 */
class IntegrationPlugin extends Model
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
     * The image url.
     *
     * @var string|null
     */
    private $img_url;

    /**
     * The URL.
     *
     * @var string|null
     */
    private $url;

    /**
     * The orderby.
     *
     * @var int
     */
    private $orderby = 0;

    /**
     * The IntegrationPlugin constructor.
     *
     * @param array $data Initial property values.
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Gets all records.
     *
     * @return mixed Array of results, false otherwise.
     */
    public static function getAll()
    {
        $sql = 'SELECT * FROM integration_plugins';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetchAll();
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
        $sql = 'DELETE FROM integration_plugins WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Creates the plugin.
     *
     * @return bool True if success, false otherwise.
     */
    public function create(): bool
    {
        $sql = 'INSERT INTO integration_plugins (name, img_url, url, orderby) 
                        VALUES (:name, :img_url, :url, :orderby)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':img_url', $this->getImgUrl(), PDO::PARAM_STR);
        $stmt->bindValue(':url', $this->getUrl(), PDO::PARAM_STR);
        $stmt->bindValue(':orderby', $this->getOrderby(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->id = $db->lastInsertId();

            return true;
        }

        $this->errors[] = 'Не удалось создать запись, попробуйте ещё раз.'; // @codeCoverageIgnore

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
     * Gets the img url.
     *
     * @return string|null
     */
    public function getImgUrl(): ?string
    {
        return $this->img_url;
    }

    /**
     * Gets the url.
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Gets the orderby.
     *
     * @return int
     */
    public function getOrderby(): int
    {
        return $this->orderby;
    }
}
