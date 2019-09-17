<?php

namespace Core;

use PDO;

/**
 * Class Model.
 *
 * @package Core
 */
abstract class Model
{
    /**
     * Gets the PDO database connection.
     *
     * @return PDO
     */
    protected static function getDB(): PDO
    {
        static $db = null;
        static $init_time = null;

        if ($db === null || time() >= $init_time) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
            $db  = new PDO($dsn, DB_USER, DB_PASS);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $init_time = time() + 60;
        }

        return $db;
    }
}
