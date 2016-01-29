<?php
namespace Olc\Advice\Expression;

use Olc\Advice\Bounce;

class Force extends Expression
{
    protected $requiredArgs = 1;

    public function run($args)
    {
        list($fn) = $this->spec;
        return array(function () use ($fn) {
            return $fn->immediate(func_get_args());
        });
    }
}
