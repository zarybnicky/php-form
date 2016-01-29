<?php
namespace Olc\Advice\Expression;

use Olc\Advice\Bounce;

class Prog2 extends Expression
{
    protected $requiredArgs = 2;

    public function run($args)
    {
        list($first, $second) = $this->spec;

        return new Bounce(
            function () use ($first, $args) {
                return $first->run($args);
            },
            function () use ($second, $args) {
                return new Bounce(
                    function () use ($second, $args) {
                        return $second->run($args);
                    },
                    null
                );
            }
        );
    }
}