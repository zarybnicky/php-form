<?php
namespace \Olc\Filter;

class Trim implements FilterInterface
{
    protected $mask;

    public function __construct($mask = " \t\n\r\0\x0B")
    {
        $this->mask = $mask;
    }

    public function filter($x)
    {
        return trim($x, $this->mask);
    }
}
