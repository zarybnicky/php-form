<?php

/**
 * Composes functions. Chains left-to-right.
 * Accepts either variadic arguments, or a list of functions as
 * the first argument.
 *
 * @return The composite function
 */
function compose(/* variadic */)
{
    $n = func_num_args();
    $fs = func_get_args();

    if ($n === 0) {
        return 'Olc\\Utility\\Direct\\identity';
    } elseif ($n === 1) {
        $f = func_get_arg(0);
        if (is_callable($f)) {
            return $f;
        } elseif (is_array($f)) {
            $fs = $f;
        } else {
            fail();
        }
    }

    return function (/* variadic */) use ($fs) {
        $n = func_num_args();
        if ($n === 0) {
            $f = array_shift($fs);
            $value = $f();
        } elseif ($n === 1) {
            $value = func_get_arg(0);
        } else {
            $value = func_get_args();
        }
        foreach ($fs as $f) {
            $value = $f($value);
        }
        return $value;
    };
}

/**
 * Calls $fn with the rest of arguments to 'apply'.
 *
 * @param function $f Function to call
 *
 * @return Result of calling $fn
 */
function apply($f /* variadic */)
{
    return call_user_func_array($f, array_slice(func_get_args(), 1));
}

function flip($f, $x, $y)
{
    return $f($y, $x);
}

/**
 * Curries $f.
 *
 * @param callable $f Function to curry
 *
 * @return function
 */
function curry($f)
{
    $args = func_get_args();
    $f = array_shift($args);
    $reflect = new \ReflectionFunction($f);
    $n = $reflect->getNumberOfParameters();
    return call_user_func_array(curryN($n, $f), $args);
}

/**
 * Curries a function of $n parameters.
 *
 * @param int      $n Number of parameters of $f
 * @param callable $f Function to curry
 *
 * @return function
 */
function curryN($n, $f)
{
    $makeAcceptor = function ($args) use ($n, $f, $makeAcceptor) {
        $args = array_merge($args, func_get_args());
        if (count($args) >= $n) {
            $ret = call_user_func_array($f, array_slice($args, 0, $n));
            return (count($args) > $n)
                ? call_user_func_array($ret, array_slice($args, $n))
                : $ret;
        } else {
            return $makeAcceptor($args);
        }
    };
    return $makeAcceptor(array_slice(func_get_args(), 2));
}

/**
 * Memoizes a function.
 *
 * @param callable $f Function to memoize
 *
 * @return callable Memoized version of $f
 */
function memoize($f)
{
    $args = array_slice(func_get_args(), 1);
    return function () use ($f, $args) {
        static $cache = array();
        $args = array_merge($args, func_get_args());
        $key = md5(serialize($args));
        if (!isset($cache[$key])) {
            $cache[$key] = call_user_func_array($f, $args);
        }
        return $cache[$key];
    };
}

/**
 * Makes a timer out of a function.
 *
 * @param calalble $f Function to time
 *
 * @return callable Timer of $f
 */
function timer($f)
{
    $args = array_slice(func_get_args(), 1);
    return function () use ($f, $args) {
        $args = array_merge($args, func_get_args());
        $start = microtime(true);
        $result = call_user_func_array($f, $args);
        //TODO: discard result? return a tuple?
        return sprintf("%f\n", microtime(true) - $start);
    };
}
