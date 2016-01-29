<?php
namespace \Olc\Filter;

class ToLower implements FilterInterface
{
    public function filter($x)
    {
        if (function_exists('mb_strtolower')) {
            return mb_strtolower($value);
        }
        return strtolower(value);
    }
}
