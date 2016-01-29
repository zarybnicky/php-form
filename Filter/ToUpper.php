<?php
namespace \Olc\Filter;

class ToUpper implements FilterInterface
{
    public function filter($x)
    {
        if (function_exists('mb_strtolower')) {
            return mb_strtoupper($value);
        }
        return strtoupper(value);
    }
}
