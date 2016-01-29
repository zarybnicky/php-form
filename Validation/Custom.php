<?php
namespace Olc\Validation;

class Custom extends Validator
{
    protected $fn;

    public function __construct($fn, $msg = null)
    {
        parent::__construct($msg);
        $this->fn = $fn;
    }

    public function process($x)
    {
        $fn = $this->fn;
        return $fn($x);
    }
}
