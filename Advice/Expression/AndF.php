<?php
namespace Olc\Advice\Expression;

use Olc\Advice\Bounce;

class AndF extends Expression
{
    protected $requiredArgs = 2;

    public function run($args)
    {
        list($first, $second) = $this->spec;

        return new Bounce(
            function () use ($first, $args) {
                return $first->run($args);
            },
            function ($return) use ($second, $args) {
                if (!$return) {
                    return $return;
                }
                return new Bounce(
                    function () use ($second, $args) {
                        return $second->run($args);
                    }
                );
            }
        );
    }
}
