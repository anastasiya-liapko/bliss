<?php

namespace App\Models;

use App\Token;
use Core\Model;
use PDO;

/**
 * Class Order.
 *
 * @package App\Models
 */
class Order extends Model
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
     * The shop id.
     *
     * @var int|null
     */
    private $shop_id;

    /**
     * The order id in the shop.
     *
     * @var string|null
     */
    private $order_id_in_shop;

    /**
     * The order price.
     *
     * @var float|null
     */
    private $order_price;

    /**
     * The goods.
     *
     * @var string|null
     */
    private $goods;

    /**
     * The status of the order.
     *
     * @var string|null
     */
    private $status;

    /**
     * The time of creation.
     *
     * @var string|null
     */
    private $time_of_creation;

    /**
     * The delivery service id.
     *
     * @var int|null
     */
    private $delivery_service_id;

    /**
     * The tracking code.
     *
     * @var string|null
     */
    private $tracking_code;

    /**
     * Order constructor.
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
     * Find the order model by the id.
     *
     * @param int $id The id.
     *
     * @return mixed The loan model if found, false otherwise.
     */
    public static function findById(int $id)
    {
        $sql = 'SELECT * FROM orders WHERE id = :id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Find the order model by the token.
     *
     * @param string $token_string The order token.
     *
     * @return mixed The request model if found, false otherwise.
     * @throws \Exception
     */
    public static function findByToken(string $token_string)
    {
        $token      = new Token($token_string);
        $token_hash = $token->getHash();

        $sql = 'SELECT * FROM orders 
                    WHERE id = (SELECT order_id FROM orders_tokens WHERE token_hash = :token_hash) LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $token_hash, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Finds an order model by a shop id and order id in shop.
     *
     * @param int $shop_id The shop id.
     * @param mixed $order_id_in_shop The order id in the shop.
     *
     * @return mixed The order object if found, false otherwise.
     */
    public static function findByOrderIdInShop(int $shop_id, $order_id_in_shop)
    {
        $sql = 'SELECT * FROM orders WHERE shop_id = :shop_id AND order_id_in_shop = :order_id_in_shop LIMIT 1';

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
        $sql = 'DELETE FROM orders WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Gets waiting delivery orders for the delivery service.
     *
     * @param string $delivery_service_slug The delivery service slug.
     *
     * @return array
     */
    public static function getByDeliveryServiceSlug(string $delivery_service_slug): array
    {
        $sql = "SELECT *
                FROM orders
                WHERE status = 'waiting_for_delivery'
                   AND tracking_code IS NOT NULL 
                   AND delivery_service_id = (SELECT id FROM delivery_services WHERE slug = :delivery_service_slug)";

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':delivery_service_slug', $delivery_service_slug, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Gets the unique order id in the shop.
     *
     * @param int $shop_id
     * @return int
     */
    public static function getUniqueOrderIdInShop(int $shop_id): int
    {
        do {
            $order_id_in_shop = mt_rand();
            $order            = static::findByOrderIdInShop($shop_id, $order_id_in_shop);
        } while (is_object($order));

        return $order_id_in_shop;
    }

    /**
     * Normalize goods.
     *
     * @param string $goods Goods.
     *
     * @return string Goods.
     */
    public static function normalizeGoods(string $goods): string
    {
        $goods = json_decode($goods, true);

        $result = '';

        foreach ($goods as $value) {
            if (isset($value['name'])) {
                $result .= $value['name'];
            }

            if (isset($value['price'])) {
                $result .= ' — ' . $value['price'] . ' руб.';
            }

            if (isset($value['quantity'])) {
                $result .= ' — ' . $value['quantity'] . ' шт.';
            }

            $result .= ";\n";
        }

        return rtrim($result, ";\n");
    }

    /**
     * Gets the order.
     *
     * @param int $order_id The order id.
     *
     * @return mixed The order or false.
     */
    public static function getOrder(int $order_id)
    {
        $sql = "SELECT o.id, o.order_id_in_shop, o.order_price, o.goods, o.status, o.time_of_creation, o.tracking_code,
                r.id AS request_id, 
                l.id AS loan_id, l.customer_id AS mfi_customer_id, l.contract_id AS mfi_contract_id, l.is_mfi_paid,
                CONCAT_WS(SPACE(1), c.last_name, c.first_name, c.middle_name) AS customer_name, 
                COALESCE(c.phone, ot.client_phone) AS customer_phone, 
                c.additional_phone AS customer_additional_phone,
                m.name AS mfi_name, 
                d.name AS delivery_service_name
                FROM orders AS o
                LEFT JOIN requests AS r
                    ON r.order_id = o.id
                LEFT JOIN clients AS c
                    ON r.client_id = c.id
                LEFT JOIN loans AS l
                    ON r.id = l.request_id
                LEFT JOIN mfi AS m
                    ON l.mfi_id = m.id
                LEFT JOIN delivery_services AS d
                    ON o.delivery_service_id = d.id
                LEFT JOIN orders_tokens AS ot
                    ON o.id = ot.order_id
                WHERE o.id = :order_id";

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = $stmt->fetch();

        if (! empty($result)) {
            $result['goods'] = json_decode($result['goods'], true);
        }

        return $result;
    }

    /**
     * Gets orders.
     *
     * @param int $shop_id The shop id.
     * @param int $type (optional) The order type.
     * @param int $offset (optional) The offset.
     * @param int $per_page (optional) The limit.
     * @param string $sort_by (optional) The sort by.
     * @param string $sort (optional) Sorting rule.
     * @param string $filter_by (optional) The filter by.
     * @param string $filter_start (optional) The filter start value.
     * @param string $filter_end (optional) The filter end value.
     * @param string $include (optional) The orders ids.
     *
     * TYPE     Statuses                              Delivery service id
     * -------------------------------------------------------------------
     * 0        all
     * 1        waiting_for_delivery                  !=1
     *
     * 2        waiting_for_delivery                  =1
     *
     * 3        declined_by_mfi,
     *          declined_by_shop,
     *          canceled_by_client,
     *          canceled_by_client_upon_receipt
     *
     * 4        pending_by_shop
     *
     * 5        pending_by_mfi,
     *          approved_by_mfi,
     *          mfi_did_not_answer
     *
     * 6        waiting_for_registration
     *
     * 7        waiting_for_payment
     *
     * 8        paid
     *
     * @return array The array of orders.
     */
    public static function getOrders(
        int $shop_id,
        int $type = 0,
        int $offset = 0,
        int $per_page = 10,
        string $sort = 'DESC',
        string $sort_by = 'order_id_in_shop',
        string $filter_by = '',
        string $filter_start = '',
        string $filter_end = '',
        string $include = ''
    ): array {
        $sort     = strtoupper($sort) === 'DESC' ? 'DESC' : 'ASC';
        $sort_sql = static::getSortSql($sort_by, $sort);

        if (! empty($filter_by)) {
            $filter_by    = static::getFilterBy($filter_by);
            $filter_start = static::getFilterStart($filter_by, $filter_start);
            $filter_end   = static::getFilterEnd($filter_by, $filter_end);
        }

        $sql = "SELECT o.id, o.order_id_in_shop, o.order_price, o.goods, o.status, o.time_of_creation, o.tracking_code,
                d.name AS delivery_service_name,
                m.name AS mfi_name
                FROM orders AS o
                LEFT JOIN delivery_services AS d
                    ON o.delivery_service_id = d.id
                LEFT JOIN requests AS r
                    ON o.id = r.order_id
                LEFT JOIN mfi AS m
                    ON r.approved_mfi_id = m.id
                WHERE o.shop_id = :shop_id
                    " . static::getTypeSql($type) . "
                    " . static::getFilterSql($filter_by, $filter_start, $filter_end) . "
                    " . static::getIncludeSql($include) . "
                " . $sort_sql . "
                LIMIT :offset, :per_page";

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':shop_id', $shop_id, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);

        if (! empty($filter_by) && ! empty($filter_start)) {
            $stmt->bindValue(':filter_start', $filter_start . '%', PDO::PARAM_STR);
        }

        if (! empty($filter_by) && ! empty($filter_end)) {
            $stmt->bindValue(':filter_end', $filter_end . '%', PDO::PARAM_STR);
        }

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = $stmt->fetchAll();

        foreach ($result as &$row) {
            $row['goods'] = json_decode($row['goods'], true);
        }

        return $result;
    }

    /**
     * Gets statistics of orders.
     *
     * @param int $shop_id The shop id.
     * @param int $type The order type.
     * @param string $filter_by (optional) The filter by.
     * @param string $filter_start (optional) The filter start value.
     * @param string $filter_end (optional) The filter end value.
     * @param string $include (optional) The orders ids.
     *
     * TYPE     Statuses                              Delivery service id
     * -------------------------------------------------------------------
     * 0        all
     *
     * 1        waiting_for_delivery                  !=1
     *
     * 2        waiting_for_delivery                  =1
     *
     * 3        declined_by_mfi,
     *          declined_by_shop,
     *          canceled_by_client,
     *          canceled_by_client_upon_receipt,
     *
     * 4        pending_by_shop
     *
     * 5        pending_by_mfi,
     *          approved_by_mfi,
     *          mfi_did_not_answer
     *
     * 6        waiting_for_registration
     *
     * 7        waiting_for_payment
     *
     * 8        paid
     *
     * @return array The statistics of pending orders.
     */
    public static function getOrdersStatistics(
        int $shop_id,
        int $type,
        string $filter_by = '',
        string $filter_start = '',
        string $filter_end = '',
        string $include = ''
    ): array {
        if (! empty($filter_by)) {
            $filter_by    = static::getFilterBy($filter_by);
            $filter_start = static::getFilterStart($filter_by, $filter_start);
            $filter_end   = static::getFilterEnd($filter_by, $filter_end);
        }

        $sql = "SELECT COUNT(o.id) AS total, SUM(o.order_price) AS total_cost
                FROM orders AS o
                WHERE o.shop_id = :shop_id
                    " . static::getTypeSql($type) . "
                    " . static::getFilterSql($filter_by, $filter_start, $filter_end) . "
                    " . static::getIncludeSql($include);

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':shop_id', $shop_id, PDO::PARAM_INT);

        if (! empty($filter_by) && ! empty($filter_start)) {
            $stmt->bindValue(':filter_start', $filter_start . '%', PDO::PARAM_STR);
        }

        if (! empty($filter_by) && ! empty($filter_end)) {
            $stmt->bindValue(':filter_end', $filter_end . '%', PDO::PARAM_STR);
        }

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Gets the type sql.
     *
     * @param int $type The type.
     *
     * @return string
     */
    private static function getTypeSql(int $type): string
    {
        $type_sql = '';

        switch ($type) {
            case 0:
                $type_sql = "";
                break;
            case 1:
                $type_sql = "AND o.status = 'waiting_for_delivery' AND o.delivery_service_id != 1";
                break;
            case 2:
                $type_sql = "AND o.status = 'waiting_for_delivery' AND o.delivery_service_id = 1";
                break;
            case 3:
                $type_sql = "AND o.status IN ('declined_by_mfi', 'declined_by_shop', 'canceled_by_client', "
                    . "'canceled_by_client_upon_receipt')";
                break;
            case 4:
                $type_sql = "AND o.status = 'pending_by_shop'";
                break;
            case 5:
                $type_sql = "AND o.status IN ('pending_by_mfi', 'approved_by_mfi', 'mfi_did_not_answer')";
                break;
            case 6:
                $type_sql = "AND o.status = 'waiting_for_registration'";
                break;
            case 7:
                $type_sql = "AND o.status = 'waiting_for_payment'";
                break;
            case 8:
                $type_sql = "AND o.status = 'paid'";
                break;
        }

        return $type_sql;
    }

    /**
     * Gets the sort sql.
     *
     * @param string $sort_by
     * @param string $sort_direction
     * @return string
     */
    private static function getSortSql(string $sort_by, string $sort_direction): string
    {
        switch ($sort_by) {
            case 'time_of_creation':
                $order_sql = "ORDER BY o.time_of_creation " . $sort_direction;
                break;
            case 'status':
                $order_sql = "ORDER BY o.status " . $sort_direction;
                break;
            case 'tracking_code':
                $order_sql = "ORDER BY o.tracking_code " . $sort_direction;
                break;
            default:
                $order_sql = "ORDER BY o.order_id_in_shop + 0 " . $sort_direction;
                break;
        }

        return $order_sql;
    }

    /**
     * Gets the filter by.
     *
     * @param string $filter_by
     *
     * @return string
     */
    private static function getFilterBy(string $filter_by): string
    {
        switch ($filter_by) {
            case 'time_of_creation':
                $filter_by = 'o.time_of_creation';
                break;
            case 'status':
                $filter_by = 'o.status';
                break;
            case 'tracking_code':
                $filter_by = 'o.tracking_code';
                break;
            default:
                $filter_by = 'o.order_id_in_shop';
                break;
        }

        return $filter_by;
    }

    /**
     * Gets the filter start.
     *
     * @param string $filter_by
     * @param string $filter_start
     *
     * @return string
     */
    private static function getFilterStart(string $filter_by, string $filter_start): string
    {
        if ($filter_by === 'o.time_of_creation' && ! empty($filter_start)) {
            $filter_start = date('Y-m-d', strtotime($filter_start));
        }

        return $filter_start;
    }

    /**
     * Gets the filter end.
     *
     * @param string $filter_by
     * @param string $filter_end
     *
     * @return string
     */
    private static function getFilterEnd(string $filter_by, string $filter_end): string
    {
        if ($filter_by === 'o.time_of_creation' && ! empty($filter_end)) {
            $filter_end = date('Y-m-d', strtotime($filter_end));
        }

        return $filter_end;
    }

    /**
     * Gets the filter sql.
     *
     * @param string $filter_by (optional) The filter by.
     * @param string $filter_start (optional) The filter start value.
     * @param string $filter_end (optional) The filter end value.
     *
     * @return string The filter sql.
     */
    private static function getFilterSql(
        string $filter_by,
        string $filter_start,
        string $filter_end
    ): string {
        $filter_sql = '';

        if (empty($filter_by)) {
            return $filter_sql;
        }

        if (! empty($filter_start) && ! empty($filter_end)) {
            if ($filter_by === 'o.time_of_creation') {
                $filter_sql = 'AND ' . $filter_by . ' >= :filter_start AND ' . $filter_by
                    . ' < DATE_ADD(:filter_end, INTERVAL 1 DAY)';
            } else {
                $filter_sql = 'AND ' . $filter_by . ' BETWEEN :filter_start AND :filter_end';
            }
        }

        if (! empty($filter_start) && empty($filter_end)) {
            $filter_sql = 'AND ' . $filter_by . ' LIKE :filter_start';
        }

        return $filter_sql;
    }

    /**
     * Gets the include sql.
     *
     * @param string $include The orders ids.
     *
     * @return string
     */
    public static function getIncludeSql(string $include): string
    {
        $include_sql = '';

        $list = preg_split('/[\s,]+/', $include, -1, PREG_SPLIT_NO_EMPTY);
        $ids  = array_unique(array_map(function ($value) {
            return abs(intval($value));
        }, $list));

        if (! empty($ids)) {
            $include_sql = 'AND o.id IN(' . implode(',', $ids) . ')';
        }

        return $include_sql;
    }

    /**
     * Check is the order belong to the shop.
     *
     * @param int $id The order id.
     * @param int $shop_id The shp id.
     *
     * @return bool True if success, false otherwise.
     */
    public static function isOrderBelongToShop(int $id, int $shop_id): bool
    {
        $sql = 'SELECT id FROM orders WHERE id = :id AND shop_id = :shop_id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':shop_id', $shop_id, PDO::PARAM_INT);
        $stmt->execute();

        return (bool)$stmt->fetch();
    }

    /**
     * Creates the order.
     *
     * @return bool
     * @throws \Exception
     */
    public function create(): bool
    {
        $this->checkIsOrderExist();

        if (! empty($this->getErrors())) {
            return false;
        }

        $this->time_of_creation = date('Y-m-d H:i:s');

        $sql = 'INSERT INTO orders (shop_id, order_id_in_shop, order_price, goods, status, time_of_creation) 
                VALUES (:shop_id, :order_id_in_shop, :order_price, :goods, :status, :time_of_creation)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':shop_id', $this->getShopId(), PDO::PARAM_INT);
        $stmt->bindValue(':order_id_in_shop', $this->getOrderIdInShop(), PDO::PARAM_STR);
        $stmt->bindValue(':order_price', $this->getOrderPrice(), PDO::PARAM_STR);
        $stmt->bindValue(':goods', $this->getGoods(), PDO::PARAM_STR);
        $stmt->bindValue(':status', $this->getStatus(), PDO::PARAM_STR);
        $stmt->bindValue(':time_of_creation', $this->getTimeOfCreation(), PDO::PARAM_STR);

        if ($stmt->execute()) {
            $this->id = $db->lastInsertId();

            return true;
        }

        $this->errors[] = 'Не удалось создать заказ.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Updates the status.
     *
     * @param string $status The status.
     *
     * @return bool True if success, false otherwise.
     */
    public function updateStatus(string $status): bool
    {
        $this->status = $status;

        $sql = 'UPDATE orders SET status = :status WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':status', $this->getStatus(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        $this->errors[] = 'Не удалось обновить заказ.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Updates the delivery service id.
     *
     * @param int $delivery_service_id The delivery service id.
     *
     * @return bool True if success, false otherwise.
     */
    public function updateDeliveryServiceId(int $delivery_service_id): bool
    {
        $this->delivery_service_id = $delivery_service_id;

        $sql = 'UPDATE orders SET delivery_service_id = :delivery_service_id WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':delivery_service_id', $this->getDeliveryServiceId(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        $this->errors[] = 'Не удалось обновить заказ.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Updates the tracking code.
     *
     * @param string $tracking_code The tracking code.
     *
     * @return bool True if success, false otherwise.
     */
    public function updateTrackingCode(string $tracking_code): bool
    {
        $this->tracking_code = $tracking_code;

        $sql = 'UPDATE orders SET tracking_code = :tracking_code WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':tracking_code', $this->getTrackingCode(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        $this->errors[] = 'Не удалось обновить заказ.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
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
            case 'waiting_for_registration':
                $status_name = 'Ожидает заполнения кредитной заявки';
                break;
            case 'pending_by_mfi':
                $status_name = 'На рассмотрении в ФО';
                break;
            case 'declined_by_mfi':
                $status_name = 'Отклонён ФО';
                break;
            case 'canceled_by_client':
                $status_name = 'Отклонён покупателем';
                break;
            case 'mfi_did_not_answer':
                $status_name = 'МФО не успели ответить';
                break;
            case 'approved_by_mfi':
                $status_name = 'Ожидает подтверждения покупателя';
                break;
            case 'pending_by_shop':
                $status_name = 'Ожидает подтверждения магазина';
                break;
            case 'waiting_for_delivery':
                $status_name = 'Ожидает доставки';
                break;
            case 'waiting_for_payment':
                $status_name = 'Ожидает оплаты';
                break;
            case 'paid':
                $status_name = 'Оплачен';
                break;
            case 'declined_by_shop':
                $status_name = 'Отклонён магазином';
                break;
            case 'canceled_by_client_upon_receipt':
                $status_name = 'Отменён покупателем при получении';
                break;
        }

        return $status_name;
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
     * Gets the shop id.
     *
     * @return int|null
     */
    public function getShopId(): ?int
    {
        return $this->shop_id;
    }

    /**
     * Gets the order id in the shop.
     *
     * @return string|null
     */
    public function getOrderIdInShop(): ?string
    {
        return $this->order_id_in_shop;
    }

    /**
     * Gets the order price.
     *
     * @return float|null
     */
    public function getOrderPrice(): ?float
    {
        return $this->order_price;
    }

    /**
     * Gets goods.
     *
     * @return string|null
     */
    public function getGoods(): ?string
    {
        return $this->goods;
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
     * Gets the time of creation.
     *
     * @return string|null
     */
    public function getTimeOfCreation(): ?string
    {
        return $this->time_of_creation;
    }

    /**
     * Gets the delivery service id.
     *
     * @return int|null
     */
    public function getDeliveryServiceId(): ?int
    {
        return $this->delivery_service_id;
    }

    /**
     * Gets the tracking code.
     *
     * @return string|null
     */
    public function getTrackingCode(): ?string
    {
        return $this->tracking_code;
    }

    /**
     * Checks is the order exists.
     *
     * @return void
     * @throws \Exception
     */
    private function checkIsOrderExist(): void
    {
        /* @var $order Order */
        if ($order = static::findByOrderIdInShop($this->shop_id, $this->order_id_in_shop)) {
            $this->errors[] = 'Заказ с идентификатором ' . $this->order_id_in_shop . ' уже создан.';
        }
    }
}
