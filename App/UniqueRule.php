<?php

namespace App;

use PDO;
use Rakit\Validation\Rule;

/**
 * Class UniqueRule.
 *
 * @package App
 */
class UniqueRule extends Rule
{
    /**
     * The message.
     *
     * @var string
     */
    protected $message = ':attribute :value уже зарегистрирован.';

    /**
     * The parameters.
     *
     * @var array
     */
    protected $fillableParams = ['table', 'column', 'except'];

    /**
     * PDO.
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * UniqueRule constructor.
     *
     * @param PDO $pdo PDO.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Checks.
     *
     * @param mixed $value The value.
     *
     * @return bool True if success, false otherwise.
     */
    public function check($value): bool
    {
        $this->requireParameters(['table', 'column']);

        $column = $this->parameter('column');
        $table  = $this->parameter('table');
        $except = $this->parameter('except');

        if ($except && $except == $value) {
            return true;
        }

        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS count FROM `{$table}` WHERE `{$column}` = :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return intval($data['count']) === 0;
    }
}
