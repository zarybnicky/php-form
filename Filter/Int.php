<?php
namespace Olc\Filter;

class Int implements FilterInterface
{
    public function filter($x)
    {
        return filter_var($x, FILTER_SANITIZE_NUMBER_INT);
    }
}