<?php

namespace App\Models;

use Core\Model;
use PDO;

/**
 * Class Loan.
 *
 * @package App\Models
 */
class Loan extends Model
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
     * The request id.
     *
     * @var int|null
     */
    private $request_id;

    /**
     * The shop id.
     *
     * @var int|null
     */
    private $shop_id;

    /**
     * The mfi id.
     *
     * @var int|null
     */
    private $mfi_id;

    /**
     * The status.
     *
     * @var string|null
     */
    private $status;

    /**
     * Is the mfi paid.
     *
     * @var int|null
     */
    private $is_mfi_paid;

    /**
     * The customer id in the mfi.
     *
     * @var mixed
     */
    private $customer_id;

    /**
     * The contract id in the mfi.
     *
     * @var mixed
     */
    private $contract_id;

    /**
     * The loan id in the mfi.
     *
     * @var mixed
     */
    private $loan_id;

    /**
     * The loan body.
     *
     * @var float|null
     */
    private $loan_body;

    /**
     * The loan cost.
     *
     * @var float|null
     */
    private $loan_cost;

    /**
     * The loan period in days.
     *
     * @var int|null
     */
    private $loan_period;

    /**
     * The loan daily percent rate.
     *
     * @var float|null
     */
    private $loan_daily_percent_rate;

    /**
     * The loan terms link.
     *
     * @var string|null
     */
    private $loan_terms_link;

    /**
     * The Loan constructor.
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
     * Find the loan model by the id.
     *
     * @param int $id The id.
     *
     * @return mixed The loan model if found, false otherwise.
     */
    public static function findById(int $id)
    {
        $sql = 'SELECT * FROM loans WHERE id = :id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Find the loan model by the request id.
     *
     * @param int $request_id The request id.
     *
     * @return mixed The loan model if found, false otherwise.
     */
    public static function findByRequestId(int $request_id)
    {
        $sql = 'SELECT * FROM loans WHERE request_id = :request_id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':request_id', $request_id, PDO::PARAM_INT);

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
        $sql = 'DELETE FROM loans WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Creates the loan.
     *
     * @return bool True if success, false otherwise.
     */
    public function create(): bool
    {
        $this->is_mfi_paid = 0;

        $sql = 'INSERT INTO loans (request_id, shop_id, mfi_id, status, customer_id, contract_id, loan_id, loan_body,
                   loan_cost, loan_period, loan_daily_percent_rate, loan_terms_link) 
                VALUES (:request_id, :shop_id, :mfi_id, :status, :customer_id, :contract_id, :loan_id, :loan_body, 
                        :loan_cost, :loan_period, :loan_daily_percent_rate, :loan_terms_link)';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':request_id', $this->getRequestId(), PDO::PARAM_INT);
        $stmt->bindValue(':shop_id', $this->getShopId(), PDO::PARAM_INT);
        $stmt->bindValue(':mfi_id', $this->getMfiId(), PDO::PARAM_INT);
        $stmt->bindValue(':status', $this->getStatus(), PDO::PARAM_STR);
        $stmt->bindValue(':customer_id', $this->getCustomerId(), PDO::PARAM_STR);
        $stmt->bindValue(':contract_id', $this->getContractId(), PDO::PARAM_STR);
        $stmt->bindValue(':loan_id', $this->getLoanId(), PDO::PARAM_STR);
        $stmt->bindValue(':loan_body', $this->getLoanBody(), PDO::PARAM_STR);
        $stmt->bindValue(':loan_cost', $this->getLoanCost(), PDO::PARAM_STR);
        $stmt->bindValue(':loan_period', $this->getLoanPeriod(), PDO::PARAM_INT);
        $stmt->bindValue(':loan_daily_percent_rate', $this->getLoanDailyPercentRate(), PDO::PARAM_STR);
        $stmt->bindValue(':loan_terms_link', $this->getLoanTermsLink(), PDO::PARAM_STR);

        if ($stmt->execute()) {
            $this->id = $db->lastInsertId();

            return true;
        }

        $this->errors[] = 'Не удалось сохранить информацию о кредите.'; // @codeCoverageIgnore

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

        $sql = 'UPDATE loans SET status = :status WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':status', $this->getStatus(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        $this->errors[] = 'Не удалось обновить данные по кредиту.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Gets the loan by id.
     *
     * @return mixed The array if found, false otherwise.
     */
    public function getLoan()
    {
        $sql = 'SELECT * FROM loans WHERE id = :id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
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
     * Gets the request id.
     *
     * @return int|null
     */
    public function getRequestId(): ?int
    {
        return $this->request_id;
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
     * Gets the mfi id.
     *
     * @return int|null
     */
    public function getMfiId(): ?int
    {
        return $this->mfi_id;
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
     * Gets is the mfi paid.
     *
     * @return int|null
     */
    public function getIsMfiPaid(): ?int
    {
        return $this->is_mfi_paid;
    }

    /**
     * Gets the customer id in the mfi.
     *
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Gets the contract id in the mfi.
     *
     * @return mixed
     */
    public function getContractId()
    {
        return $this->contract_id;
    }

    /**
     * Gets the loan id in the mfi.
     *
     * @return mixed
     */
    public function getLoanId()
    {
        return $this->loan_id;
    }

    /**
     * Gets the loan body.
     *
     * @return float|null
     */
    public function getLoanBody(): ?float
    {
        return $this->loan_body;
    }

    /**
     * Gets the loan cost.
     *
     * @return float|null
     */
    public function getLoanCost(): ?float
    {
        return $this->loan_cost;
    }

    /**
     * Gets the loan period in days.
     *
     * @return int|null
     */
    public function getLoanPeriod(): ?int
    {
        return $this->loan_period;
    }

    /**
     * Gets the loan daily percent rate.
     *
     * @return float|null
     */
    public function getLoanDailyPercentRate(): ?float
    {
        return $this->loan_daily_percent_rate;
    }

    /**
     * Gets the loan term link.
     *
     * @return string|null
     */
    public function getLoanTermsLink(): ?string
    {
        return $this->loan_terms_link;
    }
}
