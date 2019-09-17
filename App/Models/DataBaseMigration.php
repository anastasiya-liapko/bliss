<?php

namespace App\Models;

use App\Helper;
use Core\Model;
use PDO;

/**
 * Class DataBaseMigration.
 *
 * @package App\Models
 */
class DataBaseMigration extends Model
{
    /**
     * Get not completed migrations.
     *
     * @return array
     */
    public static function getNotCompletedMigrations(): array
    {
        $sql = 'SELECT * FROM db_migrations WHERE is_completed = 0';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Migration - transform goods from serialize to json encode.
     *
     * @codeCoverageIgnore
     *
     * @return bool
     */
    public static function migrationSerializeToJsonEncodeForGoods(): bool
    {
        $db = static::getDB();

        $stmt = $db->prepare('SELECT * FROM orders');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $orders = $stmt->fetchAll();

        foreach ($orders as $order) {
            if (! Helper::isSerialized($order['goods'])) {
                continue;
            }

            $order_goods = unserialize($order['goods']);

            foreach ($order_goods as &$order_item) {
                $order_item['quantity'] = isset($order_item['quantity']) ? $order_item['quantity'] : 1;
            }

            $order_goods_encoded = json_encode($order_goods);

            $stmt = $db->prepare('UPDATE orders SET goods = :goods WHERE id = :id');
            $stmt->bindValue(':id', $order['id'], PDO::PARAM_INT);
            $stmt->bindValue(':goods', $order_goods_encoded, PDO::PARAM_STR);

            if (! $stmt->execute()) {
                return false;
            }
        }

        $stmt = $db->prepare('SELECT * FROM remembered_clients');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $remembered_clients = $stmt->fetchAll();

        foreach ($remembered_clients as $remembered_client) {
            if (! Helper::isSerialized($remembered_client['goods'])) {
                continue;
            }

            $order_goods = unserialize($remembered_client['goods']);

            foreach ($order_goods as &$order_item) {
                $order_item['quantity'] = isset($order_item['quantity']) ? $order_item['quantity'] : 1;
            }

            $order_goods_encoded = json_encode($order_goods);

            $stmt = $db->prepare('UPDATE remembered_clients SET goods = :goods WHERE token_hash = :token_hash');
            $stmt->bindValue(':token_hash', $remembered_client['token_hash'], PDO::PARAM_STR);
            $stmt->bindValue(':goods', $order_goods_encoded, PDO::PARAM_STR);

            if (! $stmt->execute()) {
                return false;
            }
        }

        $stmt = $db->prepare(
            "UPDATE db_migrations SET is_completed = 1 WHERE name = 'migrationSerializeToJsonEncodeForGoods'"
        );
        $stmt->execute();

        return true;
    }

    /**
     * Migration - transform approved mfi responses from serialize to json encode.
     *
     * @codeCoverageIgnore
     *
     * @return bool
     */
    public static function migrationSerializeToJsonEncodeForApprovedMfiResponses(): bool
    {
        $db = static::getDB();

        $stmt = $db->prepare('SELECT * FROM requests');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $requests = $stmt->fetchAll();

        foreach ($requests as $request) {
            if (! Helper::isSerialized($request['approved_mfi_response'])) {
                continue;
            }

            $approved_mfi_response         = unserialize($request['approved_mfi_response']);
            $approved_mfi_response_encoded = json_encode($approved_mfi_response);

            $stmt = $db->prepare('UPDATE requests SET approved_mfi_response = :approved_mfi_response WHERE id = :id');
            $stmt->bindValue(':id', $request['id'], PDO::PARAM_INT);
            $stmt->bindValue(':approved_mfi_response', $approved_mfi_response_encoded, PDO::PARAM_STR);

            if (! $stmt->execute()) {
                return false;
            }
        }

        $stmt = $db->prepare(
            "UPDATE db_migrations SET is_completed = 1 
                            WHERE name = 'migrationSerializeToJsonEncodeForApprovedMfiResponses'"
        );
        $stmt->execute();

        return true;
    }
}
