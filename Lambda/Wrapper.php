<?php

namespace {
    function __($x)
    {
        return new Olc\Utility\Object\__($x);
    }
}

namespace Olc\Utility\Object {
    use Olc\Utility\Functional as f;
    use Olc\Utility\Direct as d;

    class __
    {
        protected static $registry = array();
        protected $storage;
        protected $chain;
        protected $queue;

        public function __construct($x)
        {
            $this->storage = $x;
            $this->chain = false;
            $this->queue = array();
        }

        public function __call($name, $args)
        {
            $f = static::makeFilter($name, $args);
            if ($this->chain) {
                $this->queue[] = $f;
                return $this;
            } else {
                return $f($this->storage);
            }
        }

        public function chain()
        {
            $this->chain = true;
            return $this;
        }

        public function value()
        {
            $this->chain = false;
            while ($f = array_shift($this->queue)) {
                $this->storage = $f($this->storage);
            }
            return $this->storage;
        }

        public static function __callStatic($name, $args)
        {
            return static::makeFilter($name, $args);
        }

        public static function makeFilter($name, $args)
        {
            $f = static::lookup($name);
            return call_user_func_array(
                'Olc\\Utility\\Functional\\makeFilter',
                array_merge(array($f), $args)
            );
        }

        public static function register($namespaces /* variadic */)
        {
            $namespaces = func_get_args();
            $fs = get_defined_functions();
            foreach ($fs['user'] as $f) {
                foreach ($namespaces as $n) {
                    if (stripos($f, $n) === 0) {
                        static::$registry[strtolower(basename($f))] = $f;
                    }
                }
            }
        }

        protected static function lookup($basename)
        {
            if (!static::$registry) {
                static::register(
                    'Olc\\Utility\\Direct'
                    //TODO: split String, Array, Functor, Monoid, ... instances
                    //central dispatch? switch(gettype($x)) {}
                );
            }
            return isset(static::$registry[strtolower($basename)])
                ? static::$registry[strtolower($basename)]
                : null;
        }
    }
}
