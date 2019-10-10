<?php

namespace App\Models;

use Core\Model;
use PDO;

/**
 * Class Request.
 *
 * @package App\Models
 */
class Request extends Model
{
    /**
     * Error messages.
     *
     * @var array
     */
    private $errors = [];

    /**
     * The record id.
     *
     * @var int|null
     */
    private $id;

    /**
     * The client id.
     *
     * @var int|null
     */
    private $client_id;

    /**
     * The shop id.
     *
     * @var int|null
     */
    private $shop_id;

    /**
     * The order id in the Bliss.
     *
     * @var int|null
     */
    private $order_id;

    /**
     * Is the test mode enabled.
     *
     * @var int
     */
    private $is_test_mode_enabled = 0;

    /**
     * Is the loan postponed.
     *
     * @var int
     */
    private $is_loan_postponed = 0;

    /**
     * The status of the request.
     *
     * @var string|null
     */
    private $status;

    /**
     * The approved mfi id.
     *
     * @var int|null
     */
    private $approved_mfi_id;

    /**
     * The approved mfi response.
     *
     * @var string|null
     */
    private $approved_mfi_response;

    /**
     * Time start.
     *
     * @var string|null
     */
    private $time_start;

    /**
     * Time finish.
     *
     * @var string|null
     */
    private $time_finish;

    /**
     * Request constructor.
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
     * Finds an request model by the id.
     *
     * @param int $id The request id.
     *
     * @return mixed The request object if found, false otherwise.
     */
    public static function findById(int $id)
    {
        $sql = 'SELECT * FROM requests WHERE id = :id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Finds an request model by a shop id and order id.
     *
     * @param int $shop_id The shop id.
     * @param int $order_id The order id in Bliss.
     *
     * @return mixed The request object if found, false otherwise.
     */
    public static function findByShopIdAndOrderId(int $shop_id, int $order_id)
    {
        $sql = 'SELECT * FROM requests WHERE shop_id = :shop_id AND order_id = :order_id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':shop_id', $shop_id, PDO::PARAM_INT);
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Finds an request model by a shop id and order id in a shop.
     *
     * @param int $shop_id The shop id.
     * @param string $order_id_in_shop The order id in the shop.
     *
     * @return mixed The request object if found, false otherwise.
     */
    public static function findByOrderIdInShop(int $shop_id, string $order_id_in_shop)
    {
        $sql = 'SELECT * FROM requests
                WHERE shop_id = :shop_id 
                    AND order_id = (
                        SELECT id FROM orders 
                        WHERE order_id_in_shop = :order_id_in_shop 
                          AND shop_id = :shop_id 
                        LIMIT 1
                        ) 
                LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':shop_id', $shop_id, PDO::PARAM_INT);
        $stmt->bindValue(':order_id_in_shop', $order_id_in_shop, PDO::PARAM_STR);

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
        $sql = 'DELETE FROM requests WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Creates the request signature.
     *
     * @param int $shop_id The shop id.
     * @param string $order_id The order id in shop.
     * @param float $order_price The order price.
     * @param string $goods The goods encoded in json.
     * @param string $callback_url The callback url.
     * @param int $is_loan_postponed Is the loan postponed.
     * @param int $is_test_mode_enabled Id the test mode enabled.
     * @param string $secret_key The shop secret key.
     *
     * @return string
     */
    public static function createRequestSignature(
        int $shop_id,
        string $order_id,
        float $order_price,
        string $goods,
        string $callback_url,
        int $is_loan_postponed,
        int $is_test_mode_enabled,
        string $secret_key
    ): string {
        return hash('sha256', 'shop_id=' . $shop_id
            . '&order_id=' . $order_id
            . '&order_price=' . $order_price
            . '&goods=' . $goods
            . '&callback_url=' . $callback_url
            . '&is_loan_postponed=' . $is_loan_postponed
            . '&is_test_mode_enabled=' . $is_test_mode_enabled
            . '&secret_key=' . $secret_key);
    }

    /**
     * Creates the old request signature.
     *
     * @param int $shop_id The shop id.
     * @param string $order_id The order id in shop.
     * @param float $order_price The order price.
     * @param string $goods The goods encoded in json.
     * @param string $callback_url The callback url.
     * @param int $is_loan_postponed Is the loan postponed.
     * @param int $is_test_mode_enabled Id the test mode enabled.
     * @param string $secret_key The shop secret key.
     *
     * @return string
     */
    public static function createOldRequestSignature(
        int $shop_id,
        string $order_id,
        float $order_price,
        string $callback_url,
        int $is_loan_postponed,
        string $goods,
        int $is_test_mode_enabled,
        string $secret_key
    ): string {
        return hash('sha256', $shop_id . $order_id . $order_price . $callback_url
            . $is_loan_postponed . $goods . $is_test_mode_enabled . $secret_key);
    }

    /**
     * Creates the request.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public function create(): bool
    {
        $this->checkIsRequestExist();

        if (! empty($this->errors)) {
            return false;
        }

        $this->status     = 'pending';
        $this->time_start = date('Y-m-d H:i:s');

        $sql = 'INSERT INTO requests (client_id, shop_id, order_id, is_test_mode_enabled, is_loan_postponed, 
                      status, time_start) 
                    VALUES (:client_id, :shop_id, :order_id, :is_test_mode_enabled, :is_loan_postponed, :status, 
                            :time_start)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':client_id', $this->getClientId(), PDO::PARAM_INT);
        $stmt->bindValue(':shop_id', $this->getShopId(), PDO::PARAM_INT);
        $stmt->bindValue(':order_id', $this->getOrderId(), PDO::PARAM_INT);
        $stmt->bindValue(':is_test_mode_enabled', $this->getIsTestModeEnabled(), PDO::PARAM_INT);
        $stmt->bindValue(':is_loan_postponed', $this->getIsLoanPostponed(), PDO::PARAM_INT);
        $stmt->bindValue(':status', $this->getStatus(), PDO::PARAM_STR);
        $stmt->bindValue(':time_start', $this->getTimeStart(), PDO::PARAM_STR);

        if ($stmt->execute()) {
            $this->id = $db->lastInsertId();

            return true;
        }

        $this->errors[] = 'Не удалось создать заявку, попробуйте ещё раз.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Updates the status.
     *
     * @param string $status Status of the request.
     *
     * STATUSES:
     * pending, declined, canceled, manual, approved, confirmed
     *
     * @return bool True if success, false otherwise.
     */
    public function updateStatus(string $status): bool
    {
        $this->status = $status;

        if (in_array($status, ['declined', 'canceled', 'confirmed'])) {
            $this->time_finish = date('Y-m-d H:i:s');
        }

        $sql = 'UPDATE requests SET status = :status, time_finish = :time_finish WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':status', $this->getStatus(), PDO::PARAM_STR);
        $stmt->bindValue(':time_finish', $this->getTimeFinish(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        $this->errors[] = 'Не удалось обновить запись, попробуйте ещё раз.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Updates the approved mfi.
     *
     * @param int $approved_mfi_id The approved mfi id.
     * @param string $approved_mfi_response The mfi response.
     *
     * @return bool True if success, false otherwise.
     */
    public function updateApprovedMfi(int $approved_mfi_id, string $approved_mfi_response): bool
    {
        $this->approved_mfi_id       = $approved_mfi_id;
        $this->approved_mfi_response = $approved_mfi_response;

        $sql = 'UPDATE requests 
                SET approved_mfi_id = :approved_mfi_id, approved_mfi_response = :approved_mfi_response 
                WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':approved_mfi_id', $this->getApprovedMfiId(), PDO::PARAM_INT);
        $stmt->bindValue(':approved_mfi_response', $this->getApprovedMfiResponse(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        $this->errors[] = 'Не удалось обновить запись, попробуйте ещё раз.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Gets info of the request include a client info.
     *
     * @return mixed The info of the request, false otherwise.
     */
    public function getCreditingData()
    {
        $sql = 'SELECT rq.id AS request_id, rq.is_test_mode_enabled,
                o.order_price, o.goods,
                c.id AS client_id,
                c.last_name AS client_last_name,
                c.first_name AS client_first_name,
                c.middle_name AS client_middle_name,
                c.birth_date AS client_birth_date,
                c.birth_place AS client_birth_place,
                c.sex AS client_sex,
                c.is_last_name_changed AS client_is_last_name_changed,
                c.previous_last_name AS client_previous_last_name,
                c.tin AS client_tin,
                c.snils AS client_snils,
                c.passport_number AS client_passport_number,
                c.passport_division_code AS client_passport_division_code,
                c.passport_issued_by AS client_passport_issued_by,
                c.passport_issued_date AS client_passport_issued_date,
                c.workplace AS client_workplace,
                c.salary AS client_salary,
                c.reg_zip_code AS client_reg_zip_code,
                c.reg_city AS client_reg_city,
                c.reg_street AS client_reg_street,
                c.reg_building AS client_reg_building,
                c.reg_apartment AS client_reg_apartment,
                c.is_address_matched AS client_is_address_matched,
                c.fact_zip_code AS client_fact_zip_code,
                c.fact_city AS client_fact_city,
                c.fact_street AS client_fact_street,
                c.fact_building AS client_fact_building,
                c.fact_apartment AS client_fact_apartment,
                c.email AS client_email,
                c.phone AS client_phone,
                c.additional_phone AS client_additional_phone,
                rc.sms_code, rc.callback_url
                FROM requests AS rq
                INNER JOIN clients AS c
                    ON rq.client_id = c.id
                INNER JOIN orders AS o
                    ON rq.order_id = o.id
                LEFT JOIN remembered_clients AS rc
                    ON rq.shop_id = rc.shop_id
                       AND o.order_id_in_shop = rc.order_id
                WHERE rq.id = :id
                LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Gets the status name.
     *
     * @return string
     */
    public function getStatusName(): string
    {
        $status_name = '';

        switch ($this->getStatus()) {
            case 'pending':
                $status_name = 'В процессе';
                break;
            case 'declined':
                $status_name = 'Отклонена';
                break;
            case 'canceled':
                $status_name = 'Отменена клиентом';
                break;
            case 'manual':
                $status_name = 'Требует решение менеджера';
                break;
            case 'approved':
                $status_name = 'Одобрена';
                break;
            case 'confirmed':
                $status_name = 'Подтверждена клиентом';
                break;
            case 'waiting_for_limit':
                $status_name = 'Ожидает одобрения лимита';
                break;
        }

        return $status_name;
    }

    /**
     * Gets the timer end Unix timestamp in seconds.
     *
     * @param int $request_max_time The request max time.
     *
     * @return int
     */
    public function getTimerEnd(int $request_max_time): int
    {
        return strtotime($this->getTimeStart(), time()) + $request_max_time;
    }

    /**
     * Gets callback url with parameters.
     *
     * @param string $request_status The request status.
     * @param string $order_id The order id.
     * @param string $shop_secret_key The shop secret key.
     * @param string $callback_url The callback url.
     * @param int $is_old_integration Is the old integration.
     *
     * @return string
     */
    public function getCallbackUrlWithParameters(
        string $request_status,
        string $order_id,
        string $shop_secret_key,
        string $callback_url,
        int $is_old_integration
    ): string {
        $callback_url_decoded = rawurldecode($callback_url);

        if (strpos($callback_url_decoded, '?') === false) {
            $callback_url_decoded .= '?';
        } else {
            $callback_url_decoded .= '&';
        }

        // TODO refactor it after update all shops.
        if ($is_old_integration) {
            $signature = hash('sha256', $order_id . $this->getId() . $request_status
                . $this->getIsTestModeEnabled() . $shop_secret_key);
        } else {
            $signature = hash('sha256', 'order_id=' . $order_id
                . '&request_id=' . $this->getId()
                . '&status=' . $request_status
                . '&is_test_mode_enabled=' . $this->getIsTestModeEnabled()
                . '&secret_key=' . $shop_secret_key);
        }

        return $callback_url_decoded
            . 'order_id=' . $order_id
            . '&request_id=' . $this->getId()
            . '&status=' . $request_status
            . '&is_test_mode_enabled=' . $this->getIsTestModeEnabled()
            . '&signature=' . $signature;
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
     * Gets the client id.
     *
     * @return int|null
     */
    public function getClientId(): ?int
    {
        return $this->client_id;
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
     * Gets the order id.
     *
     * @return int|null
     */
    public function getOrderId(): ?int
    {
        return $this->order_id;
    }

    /**
     * Gets is the test mode enabled.
     *
     * @return int
     */
    public function getIsTestModeEnabled(): int
    {
        return $this->is_test_mode_enabled;
    }

    /**
     * Gets is the loan postponed.
     *
     * @return int
     */
    public function getIsLoanPostponed(): int
    {
        return $this->is_loan_postponed;
    }

    /**
     * Gets the status.
     *
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Gets the approved mfi id.
     *
     * @return int|null
     */
    public function getApprovedMfiId(): ?int
    {
        return $this->approved_mfi_id;
    }

    /**
     * Gets the approved mfi response.
     *
     * @return string|null
     */
    public function getApprovedMfiResponse(): ?string
    {
        return $this->approved_mfi_response;
    }

    /**
     * Gets the time start.
     *
     * @return string|null
     */
    public function getTimeStart(): ?string
    {
        return $this->time_start;
    }

    /**
     * Gets the time finish.
     *
     * @return string|null
     */
    public function getTimeFinish(): ?string
    {
        return $this->time_finish;
    }

    /**
     * Checks is the request exists.
     *
     * @return void
     * @throws \Exception
     */
    private function checkIsRequestExist(): void
    {
        /* @var $request Request */
        if ($request = static::findByShopIdAndOrderId($this->shop_id, $this->order_id)) {
            $this->errors[] = 'Заявка уже создана. Статус — "' . $request->getStatusName() . '"';
        }
    }
}
