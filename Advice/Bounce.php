<?php
namespace Olc\Advice;

class Bounce
{
    protected $current;
    protected $next;

    public function __construct($current, $next)
    {
        $this->current = $current;
        $this->next = $next;
    }

    public function current()
    {
        $fn = $this->current;
        return $fn();
    }

    public function next($arg)
    {
        $fn = $this->next;
        return $fn ? $fn($arg) : $arg;
    }
}