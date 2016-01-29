<?php
namespace Olc\Advice\Expression;

use Olc\Advice\Bounce;

class Apply extends Expression
{
    protected $requiredArgs = 2;

    public function run($args)
    {
        $spec = $this->spec;
        $sink = array_shift($spec);
        $source = array_shift($spec);
        $rest = $spec;

        return new Bounce(
            function () use ($source, $args) {
                return $source->run($args);
            },
            $this->recur($sink, $rest, $args)
        );

        $sink = $this->spec[0];
        $sources = array_slice($this->spec, 1);;

        $newArgs = array();
        foreach ($sources as $source) {
            $newArgs = array_merge($newArgs, $source->run($args));
        }
        return $sink->run($newArgs);
    }

    public function recur($sink, $rest, $args)
    {
        if (!$rest) {
            return function ($return) use ($sink) {
                return $sink->run($return);
            };
        }

        $recur = array($this, 'recur');
        return function ($return) use ($recur, $sink, $rest, $args) {
            $fn = array_shift($rest);
            return new Bounce(
                function () use ($fn, $args) {
                    return $fn->run($args);
                },
                function ($return2) use ($recur, $sink, $rest, $args, $return) {
                    return new Bounce(
                        function () use ($return, $return2) {
                            return array_merge($return, $return2);
                        },
                        call_user_func($recur, $sink, $rest, $args)
                    );
                }
            );
        };
    }
}
