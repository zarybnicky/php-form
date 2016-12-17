<?php
namespace Olc\Data;

interface IMonad
{
    /**
     * Injects a value into the monad
     *
     * @param any $x Value to be injected
     *
     * @return IMonad
     */
    public static function pure($x);

    /**
     * Sequentially compose two actions, passing any value produced by the
     * first as an argument to the second.
     *
     * @param (x->IMonad) $f A function producing a monad
     *
     * @return IMonad
     */
    public function bind(callable $f);

    /**
     * Sequentially compose two actions, discarding any value produced by the
     * first.
     *
     * @param IMonad $n Second operand
     *
     * @return IMonad
     */
    public function seq(IMonad $m);

    /**
     * Extracts the value stored in the monad
     *
     * @return any
     */
    public function extract();
}
