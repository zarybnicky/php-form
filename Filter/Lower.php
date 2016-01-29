<?php
namespace \Olc\Filter;

class Lower implements FilterInterface
{
    public function filter($x)
    {
        return preg_replace('/[^a-z]/', '', $x);
    }
}
