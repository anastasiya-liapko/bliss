<?php

namespace App;

use Rakit\Validation\Rule;

/**
 * Class PlainRule.
 *
 * @package App
 */
class PlainRule extends Rule
{
    /**
     * The message.
     *
     * @var string
     */
    protected $message = ':attribute — поле должно быть строкой или числом и не должно содержать HTML-тэги';

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
        return (is_string($value) || is_numeric($value)) && $value == strip_tags($value);
    }
}
