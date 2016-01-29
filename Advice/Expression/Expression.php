<?php
namespace Olc\Advice\Expression;

use InvalidArgumentException;
use Olc\Advice\Bounce;

abstract class Expression
{
    protected $requiredArgs = 0;

    public function __construct()
    {
        if (func_num_args() < $this->requiredArgs) {
            throw new InvalidArgumentException(
                get_class($this) . ' requires ' . $this->requiredArgs . ' arguments.'
            );
        }
        $this->spec = func_get_args();
    }

    public function __invoke()
    {
        return $this->run(func_get_args());
    }

    public function immediate($args)
    {
        $stack = array($this->run($args));

        while ($stack[0] instanceof Bounce) {
            $current = array_pop($stack);
            if ($current instanceof Bounce) {
                $stack[] = $current;
                $stack[] = $current->current();
            } else {
                $next = array_pop($stack);
                $stack[] = $next->next($current);
            }
        }

        return $stack[0];
    }

    abstract public function run($args);

    public function __toString()
    {
        $children = array_map(
            function ($x) {
                return implode("\n", array_map(function ($x) {return "  $x";}, explode("\n", $x)));
            },
            array_filter($this->spec, function ($x) {return $x instanceof self;})
        );
        return str_replace('Olc\\Advice\\Expression\\', '', get_class($this))
            . ($children ? "\n" : '')
            . implode("\n", $children);
    }
}
