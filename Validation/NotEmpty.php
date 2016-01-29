<?php
namespace Olc\Validation;

class NotEmpty extends Validator
{
    protected $errorMsg = 'Pole nesmí být prázdné.';

    protected function process($x)
    {
        return (bool) $x;
    }
}
