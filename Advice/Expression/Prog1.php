<?php
namespace Olc\Advice\Expression;

use Olc\Advice\Bounce;

class Prog1 extends Expression
{
    protected $requiredArgs = 2;

    public function run($args)
    {
        list($first, $second) = $this->spec;

        return new Bounce(
            function () use ($first, $args) {
                return $first->run($args);
            },
            function ($ret) use ($second, $args) {
                return new Bounce(
                    function () use ($second, $args) {
                        return $second->run($args);
                    },
                    function () use ($ret) {
                        return $ret;
                    }
                );
            }
        );
    }
}