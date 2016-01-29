<?php
namespace Olc\Validation;

/**
 * Describes a validator.
 */
interface ValidatorInterface
{
    /**
     * Validates `$x`.
     *
     * @param unknown $x Value to validate
     *
     * @return bool
     */
    public function validate($x);

    /**
     * Returns validation errors (messages to user).
     *
     * @return array
     */
    public function getMessages();
}
