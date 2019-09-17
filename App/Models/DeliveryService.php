<?php

namespace App\Models;

use Core\Model;
use PDO;

/**
 * Class DeliveryService.
 *
 * @package App\Models
 */
class DeliveryService extends Model
{
    /**
     * Gets all records.
     *
     * @return array Array of results.
     */
    public static function getAll(): array
    {
        $sql = 'SELECT * FROM delivery_services';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Gets the delivery service name.
     *
     * @param int $id The delivery service id.
     *
     * @return mixed The delivery service name if found, false otherwise.
     */
    public static function getName(int $id)
    {
        $sql = 'SELECT name FROM delivery_services WHERE id = :id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
}
