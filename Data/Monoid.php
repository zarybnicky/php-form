<?php
namespace Olc\Data;

class Monoid
{
    protected $mempty;
    protected $mappend;
    protected $pure;

    public function __construct($mempty, $mappend, $pure = null)
    {
        $this->mempty = $mempty;
        $this->mappend = $mappend;
        $this->pure = $pure;
    }

    public function __invoke()
    {
        return $this->mconcat(func_get_args());
    }

    public function mempty()
    {
        return $this->mempty;
    }

    public function mappend($x, $y)
    {
        $mappend = $this->mappend;
        $pure = $this->pure;
        return $pure
            ? $mappend($pure($x), $pure($y))
            : $mappend($x, $y);
    }

    public function mconcat(array $xs)
    {
        $xs = $this->pure ? array_map($this->pure, $xs) : $xs;
        return array_reduce($xs, $this->mappend, $this->mempty);
    }

    public function star()
    {
        return new Monoid($this->mempty, $this->mappend, array($this, 'mconcat'));
    }
}
