<?php
namespace Olc\Filter;

class IntCast implements FilterInterface
{
    public function filter($x)
    {
        return intval($x);
    }
}
