<?php namespace Olc\Data;

class Container
{
    protected $value;

    protected function __construct($value)
    {
        $this->value = $value;
    }

    protected function getValue()
    {
        return $this->value;
    }
}
