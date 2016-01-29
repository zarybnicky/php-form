<?php
namespace Olc\Filter;

class Custom implements FilterInterface
{
    protected $fn;

    public function __construct(callable $fn)
    {
        $this->fn = $fn;
    }

    public function filter($x)
    {
        return $this->fn($x);
    }
}
