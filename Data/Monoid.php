<?php
namespace Olc\Data;

class Monoid
{
    protected $mempty;
    protected $mappend;
    protected $lift;

    public function __construct($mempty, $mappend, $lift = null)
    {
        $this->mempty = $mempty;
        $this->mappend = $mappend;
        $this->lift = $lift;
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
        $lift = $this->lift;
        return $lift
            ? $mappend($lift($x), $lift($y))
            : $mappend($x, $y);
    }

    public function mconcat(array $xs)
    {
        $xs = $this->lift ? array_map($this->lift, $xs) : $xs;
        return array_reduce($xs, $this->mappend, $this->mempty);
    }

    public function star()
    {
        return new Monoid($this->mempty, $this->mappend, array($this, 'mconcat'));
    }
}
