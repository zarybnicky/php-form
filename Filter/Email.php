<?php
namespace Olc\Filter;

class Email implements FilterInterface
{
    public function filter($x)
    {
        return filter_var($x, FILTER_SANITIZE_EMAIL);
    }
}
