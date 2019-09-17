<?php

namespace App\Models;

use Core\Model;
use PDO;

/**
 * Class LockedPhone.
 *
 * @package App\Models
 */
class LockedPhone extends Model
{
    /**
     * Locks the phone.
     *
     * @param string $phone The phone.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public static function lock(string $phone): bool
    {
        $sql = 'INSERT INTO locked_phones (phone, locked_until) VALUES (:phone, :locked_until)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindValue(':locked_until', date('Y-m-d H:i:s', time() + 60 * 60 * 24), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Checks is the phone locked.
     *
     * @param string $phone The phone.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public static function isLocked(string $phone): bool
    {
        $sql = 'SELECT * FROM locked_phones WHERE phone = :phone AND locked_until > :now';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':phone', preg_replace('/[-)+(\s]/', '', $phone), PDO::PARAM_STR);
        $stmt->bindValue(':now', date('Y-m-d H:i:s', time()), PDO::PARAM_STR);

        $stmt->execute();

        return (bool)$stmt->fetch();
    }

    /**
     * Deletes expired records.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public static function deleteExpiredRecords(): bool
    {
        $sql = 'DELETE FROM locked_phones WHERE :now > locked_until';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':now', date('Y-m-d H:i:s', time()), PDO::PARAM_STR);

        return $stmt->execute();
    }
}
