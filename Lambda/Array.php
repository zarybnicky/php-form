<?php

namespace Olc\Utility\Namespace;

function tap(callable $f, array $xs)
{
    foreach ($xs as $x) {
        $f($x);
    }
    return $xs;
}

function map(callable $f, array $xs)
{
    $out = array();
    foreach ($xs as $k => $x) {
        $out[$k] = $f($x);
    }
    return $out;
}

function head(array $xs)
{
    return reset($xs);
}

function init(array $xs)
{
    return take($xs, count($xs) - 2);
}

function take($n, array $xs)
{
    if ($n === 0) {
        return array();
    } else {
        return array_slice($xs, 0, $n);
    }
}

function takeWhile(callable $f, array $xs)
{
    foreach ($xs as $i => $x) {
        if (!$f($x)) {
            return array_slice($xs, 0, $i);
        }
    }
    return $xs;
}

function filter(callable $f, array $xs)
{
    return array_filter($xs, $f);
}

function scan(callable $f, array $xs)
{
    $out = $scanned = array();
    while ($xs) {
        $scanned[] = array_shift($xs);
        $out[] = $f($scanned);
    }
    return $out;
}

function prepend($x, array $xs)
{
    array_unshift($xs, $x);
    return $xs;
}

function append($x, array $xs)
{
    $xs[] = $x;
    return $xs;
}

function transpose(array $xs)
{
    //special case: empty matrix
    if (!$xs) {
        return array();
    }
    //special case: row matrix
    if (count($xs) == 1) {
        return array_chunk($xs[0], 1);
    }
    return call_user_func_array(
        'array_map',
        array_merge(array(null), $xs)
    );
}

function intersperse($sep, array $xs)
{
    if (!$xs) {
        return array();
    }
    $out = array(array_shift($xs));
    foreach ($xs as $x) {
        $out[] = $sep;
        $out[] = $x;
    }
    return $out;
}

function any(callable $f, array $xs)
{
    foreach ($xs as $x) {
        if ($f($x)) {
            return true;
        }
    }
    return false;
}

function all(callable $f, array $xs)
{
    foreach ($xs as $x) {
        if (!$f($x)) {
            return false;
        }
    }
    return true;
}

function span(callable $f, array $xs)
{
    $len = 0;
    foreach ($xs as $x) {
        if (!$f($x)) {
            break;
        } else {
            $len++;
        }
    }
    return array(
        array_slice($xs, 0, $len),
        array_slice($xs, $len)
    );
}

function groupWith(callable $f, array $xs)
{
    $result = array();
    while ($xs) {
        list($span, $xs) = span($f, $xs);
        $result[] = $span;
    }
    return $result;
}

function adjust(callable $f, $n, array $xs)
{
    $xs[$n - 1] = $f($xs[$n - 1]);
    return $xs;
}

function zip(array $xs)
{
    $out = array();
    while (all($xs, 'count')) {
        $elem = array();
        foreach ($xs as &$x) {
            $elem[] = array_shift($x);
        }
        $out[] = $elem;
    }
    return $out;
}

function sort($xs, $f = null)
{
    ($f === null) ? sort($xs) : usort($xs, $f);
    return $xs;
}
