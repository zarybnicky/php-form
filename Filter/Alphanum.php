<?php
namespace Olc\Filter;

class Alphanum implements FilterInterface
{
    public function filter($x)
    {
        return preg_replace('/[^a-z0-9]/i', '', $x);
    }
}
