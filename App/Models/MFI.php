<?php

namespace App\Models;

use Core\Model;
use PDO;

/**
 * Class MFI.
 *
 * @package App\Models
 */
class MFI extends Model
{
    /**
     * Gets the mfi data by id.
     *
     * @param int $id The mfi id.
     *
     * @return mixed The array if found, false otherwise.
     */
    public static function getById(int $id)
    {
        $sql = 'SELECT * FROM mfi WHERE id = :id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Gets mfi data by id
     *
     * @param int $mfi_id The mfi id.
     * @param int $shop_id The shop id.
     *
     * @return mixed The mfi api parameters if found, false otherwise.
     */
    public static function getApiParametersForShop(int $mfi_id, int $shop_id)
    {
        $sql = 'SELECT mfi_api_parameters FROM mfi_shop_cooperation 
                WHERE mfi_id = :mfi_id AND shop_id = :shop_id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':mfi_id', $mfi_id, PDO::PARAM_INT);
        $stmt->bindValue(':shop_id', $shop_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = false;

        while ($row = $stmt->fetch()) {
            $result = $row['mfi_api_parameters'];
        }

        return json_decode($result, true);
    }

    /**
     * Gets mfi for the shop.
     *
     * @param int $shop_id The shop id.
     * @param float $loan_sum The loan sum.
     * @param int $can_loan_postponed (optional) Can loan be postponed.
     *
     * @return array The array of mfi.
     */
    public static function getForShop(int $shop_id, float $loan_sum, int $can_loan_postponed = 0): array
    {
        $sql = 'SELECT m.id, m.name, m.phone, m.email, m.time_limit, m.slug, msc.mfi_api_parameters
                FROM mfi AS m
                INNER JOIN mfi_shop_cooperation AS msc
                    ON m.id = msc.mfi_id 
                    AND msc.shop_id = :shop_id 
                    AND :loan_sum >= m.min_loan_sum
                    AND :loan_sum <= m.max_loan_sum
                    AND m.can_loan_postponed = :can_loan_postponed ORDER BY m.priority';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':shop_id', $shop_id, PDO::PARAM_INT);
        $stmt->bindValue(':loan_sum', $loan_sum, PDO::PARAM_STR);
        $stmt->bindValue(':can_loan_postponed', $can_loan_postponed, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Gets did not answered mfi.
     *
     * @param int $request_id The request id.
     *
     * @return array The array of mfi.
     */
    public static function getDidNotAnswered(int $request_id): array
    {
        $sql = "SELECT m.id, m.name, m.phone, m.email, m.time_limit, m.slug, msc.mfi_api_parameters
                FROM mfi AS m
                INNER JOIN mfi_responses AS mr
                    ON mr.request_id = :request_id
                    AND mr.status = 'did_not_have_time'
                    AND m.id = mr.mfi_id
                INNER JOIN requests AS rq
                    ON rq.id = :request_id
                INNER JOIN mfi_shop_cooperation AS msc
                    ON m.id = msc.mfi_id
                    AND rq.shop_id = msc.shop_id
                ORDER BY m.priority";

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':request_id', $request_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Creates the response.
     *
     * @param int $mfi_id The mfi id.
     * @param int $request_id The request id.
     * @param string $status The status.
     *
     * STATUSES:
     * approved, declined, did_not_have_time
     *
     * @return bool True if success, false otherwise.
     */
    public static function createResponse(int $mfi_id, int $request_id, string $status): bool
    {
        $sql = 'INSERT INTO mfi_responses (mfi_id, request_id, status, time_response) 
                    VALUES (:mfi_id, :request_id, :status, :time_response)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':mfi_id', $mfi_id, PDO::PARAM_INT);
        $stmt->bindValue(':request_id', $request_id, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':time_response', date('Y-m-d H:i:s'), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Updates the response.
     *
     * @param int $mfi_id The mfi id.
     * @param int $request_id The request id.
     * @param string $status The status.
     *
     * STATUSES:
     * approved, declined, did_not_have_time
     *
     * @return bool True if success, false otherwise.
     */
    public static function updateResponse(int $mfi_id, int $request_id, string $status): bool
    {
        $sql = 'UPDATE mfi_responses SET status = :status, time_response = :time_response
                    WHERE mfi_id = :mfi_id AND request_id = :request_id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':time_response', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':mfi_id', $mfi_id, PDO::PARAM_INT);
        $stmt->bindValue(':request_id', $request_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Gets statuses of mfi responses by id.
     *
     * @param int $request_id The request id.
     * @param string $status The responses status.
     *
     * STATUSES:
     * approved, declined, did_not_have_time
     *
     * @return mixed The array if found, false otherwise.
     */
    public static function getResponses(int $request_id, string $status)
    {
        $sql = 'SELECT * FROM mfi_responses WHERE request_id = :request_id AND status = :status';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':request_id', $request_id, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Gets the request max time.
     *
     * @param int $shop_id The shop id.
     * @param float $loan_sum The loan sum.
     * @param int $can_loan_postponed (optional) Can loan be postponed.
     *
     * @return int The request max time. 180 is default value.
     */
    public static function getRequestMaxTime(int $shop_id, float $loan_sum, int $can_loan_postponed = 0): int
    {
        $sql = 'SELECT SUM(m.time_limit) as request_max_time 
                FROM mfi AS m
                INNER JOIN mfi_shop_cooperation AS msc
                    ON m.id = msc.mfi_id
                    AND msc.shop_id = :shop_id
                    AND :loan_sum >= m.min_loan_sum
                    AND :loan_sum <= m.max_loan_sum
                    AND m.can_loan_postponed = :can_loan_postponed';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':shop_id', $shop_id, PDO::PARAM_INT);
        $stmt->bindValue(':loan_sum', $loan_sum, PDO::PARAM_STR);
        $stmt->bindValue(':can_loan_postponed', $can_loan_postponed, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = $stmt->fetch();

        return (int)$result['request_max_time'] !== 0 ? (int)$result['request_max_time'] : 180;
    }

    /**
     * Gets mfi terms.
     *
     * @param int $mfi_id The mfi id.
     *
     * @return array
     */
    public static function getMFITerms(int $mfi_id): array
    {
        switch ($mfi_id) {
            case 1:
                $terms = [
                    'С правилами предоставления кредита вы можете ознакомиться, перейдя по <a href="//static.webbankir'
                    . '.com/public/docs/regulations_pos.pdf?_ga=2.195949356.1692079331.1562574080-1356716508'
                    . '.1561375752" class="document link link_black" target="_blank" rel="noreferrer">ссылке</a>.',
                    'С условиями использования и возврата кредита вы можете ознакомиться, перейдя по '
                    . '<a href="//static.webbankir.com/public/docs/rules_pos.pdf?_ga=2.195949356.1692079331.'
                    . '1562574080-1356716508.1561375752" class="document link link_black" target="_blank" '
                    . 'rel="noreferrer">ссылке</a>.',
                ];
                break;
            default:
                $terms = [];
                break;
        }

        return $terms;
    }
}
