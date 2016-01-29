<?php
namespace Olc\Advice\Expression;

class Call extends Expression
{
    protected $requiredArgs = 1;

    public function run($args)
    {
        list($fn) = $this->spec;
        return call_user_func_array($this->spec[0], $args);
    }

    public function __toString()
    {
        if (is_array($this->spec[0])) {
            return 'Call ' . get_class($this->spec[0][0]);
        } else {
            return 'Call';
        }
    }
}