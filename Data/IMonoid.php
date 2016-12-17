<?php
namespace Olc\Data;

interface IMonoid
{
    /**
     * Returns the identity of mappend
     *
     * @return IMonoid
     */
    public static function mempty();

    /**
     * An associative operation
     *
     * @param IMonad $a First operand
     * @param IMonad $b Second operand
     *
     * @return IMonoid
     */
    public static function mappend(IMonad $a, IMonad $b);

    /**
     * Folds a list using the operation
     *
     * @param array $xs Operand list
     *
     * @return IMonoid
     */
    public static function mconcat(array $xs);
}
