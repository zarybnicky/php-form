<?php
namespace Olc\Utility;

function add($x, $y)
{
    return $x + $y;
}

function avg(array $xs)
{
    if (!$xs) {
        return NAN;
    }
    return array_sum($xs) / count($xs);
}

function clamp($low, $high, $x)
{
    if ($x <= $low) {
        return $low;
    } elseif ($x >= $high) {
        return $high;
    } else {
        return $x;
    }
}

function dec($x)
{
    return --$x;
}

function divide($x, $y)
{
    return $x / $y;
}

function inc($x)
{
    return ++$x;
}

function mathMod($x, $p)
{
    if (!is_int($x) || !is_int($p) || $p < 1) {
        return NAN;
    }
    return (($x % $p) + $p) % $p;
}

function median(array $xs)
{
    $len = count($xs);
    if (!$len) {
        return NAN;
    }
    $width = 2 - $len % 2;
    $index = ($len - $width) / 2;
    sort($xs);
    return avg(array_slice($xs, $index, $width));
}

function modulo($x, $p)
{
    return $x % $p;
}

function multiply($x, $y)
{
    return $x * $y;
}

function negate($x)
{
    return -$x;
}

function subtract($x, $y)
{
    return $x - $y;
}
