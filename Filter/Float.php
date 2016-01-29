<?php
namespace Olc\Filter;

class Float implements FilterInterface
{
    public function filter($x)
    {
        return filter_var($x, FILTER_SANITIZE_NUMBER_FLOAT);
    }
}