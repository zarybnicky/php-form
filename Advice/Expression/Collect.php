<?php
namespace Olc\Advice\Expression;

use Olc\Advice\Bounce;

class Collect extends Expression
{
    protected $requiredArgs = 1;

    public function run($args)
    {
        list($fn) = $this->spec;
        return new Bounce(
            function () use ($fn, $args) {
                return $fn->run($args);
            },
            function ($ret) {
                return array($ret);
            }
        );
    }
}