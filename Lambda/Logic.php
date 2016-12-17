<?php
namespace Olc\Utility;

function allPass(array $fs, $x)
{
    foreach ($fs as $f) {
        if (!$f($x)) {
            return false;
        }
    }
    return true;
}

function andF($x, $y)
{
    return $x && $y;
}

function anyPass(array $fs, $x)
{
    foreach ($fs as $f) {
        if ($f($x)) {
            return true;
        }
    }
    return false;
}

function both(callable $f, callable $g, $x)
{
    if (!($result = $f($x))) {
        return $result;
    } else {
        return $g($x);
    }
}

function complement(callable $f, $x)
{
    return !$f($x);
}

function cond(array $preds, $x)
{
    foreach ($preds as $pred) {
        list($test, $f) = $pred;
        if ($test($x)) {
            return $f($x);
        }
    }
    return null;
}

function defaultTo($default, $x)
{
    return $x ?: $default;
}

function either(callable $f, callable $g, $x)
{
    if ($result = $f($x)) {
        return $result;
    } else {
        return $g($x);
    }
}

function ifElse($cond, $onTrue, $onFalse, $x)
{
    if ($cond($x)) {
        return $onTrue($x);
    } else {
        return $onFalse($x);
    }
}

function not($x)
{
    return !$x;
}

function orF($x, $y)
{
    return $x || $y;
}

function unless($cond, $onFalse, $x)
{
    if (!$cond($x)) {
        return $onFalse($x);
    } else {
        return $x;
    }
}

function when($cond, $onTrue, $x)
{
    if ($cond($x)) {
        return $onTrue($x);
    } else {
        return $x;
    }
}
