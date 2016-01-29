<?php
namespace \Olc\Filter;

class Upper implements FilterInterface
{
    public function filter($x)
    {
        return preg_replace('/[^A-Z]/', '', $x);
    }
}
