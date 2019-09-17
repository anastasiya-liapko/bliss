<?php

namespace App;

use Rakit\Validation\Rule;

/**
 * Class RusDateRule.
 *
 * @package App
 */
class DateRule extends Rule
{
    /**
     * The message.
     *
     * @var string
     */
    protected $message = ':attribute — поле должно содержать дату в формате 01.01.1970';

    /**
     * PlainRule constructor.
     *
     */
    public function __construct()
    {
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
        return (bool)preg_match('/^(0[1-9]|[12]\d|3[01])\.((0[1-9]|1[0-2])\.[12]\d{3})$/', $value);
    }
}
