<?php namespace Olc\Data;

include 'autoload.php';

abstract class Monad extends Container
{
    abstract public static function unit($x);

    public function map($f)
    {
        $class = get_class($this);
        return $this->bind(
            function ($x) use ($f, $class) {
                return $class::unit($f($x));
            }
        );
    }

    public function join()
    {
        return $this->bind(
            function ($x) {
                return $x;
            }
        );
    }

    public function bind($f)
    {
        return $this->map($f)->join();
    }
}

class MonadState extends Monad
{
    public static function unit($x)
    {
        return new self(
            function ($s) use ($x) {
                return array($x, $s);
            }
        );
    }

    public function bind($f)
    {
        $run = $this->getValue();
        return new self(
            function ($s) use ($f, $run) {
                list($a, $s_) = $run($s);
                $run_ = $f($a)->getValue();
                return $run_($s_);
            }
        );
    }

    public function get()
    {
        return new self(
            function ($s) {
                return array($s, $s);
            }
        );
    }

    public function put($x)
    {
        return new self(
            function () use ($x) {
                return array(null, $x);
            }
        );
    }

    public function run($s)
    {
        $run = $this->getValue();
        return $run($s);
    }
}

$state = MonadState::unit(4);
var_dump(
    $state->map(
        function ($x) {
            return $x + 1;
        }
    )->run(1)
);
var_dump(
    $state->bind(
        function ($x) use ($state) {
            return $state->put(2);
        }
    )->run(1)
);

//TODO: Trampoline monad: https://gist.github.com/MgaMPKAy/7976436