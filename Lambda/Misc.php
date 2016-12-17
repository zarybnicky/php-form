<?php

namespace Olc\Utility\Misc;

function identity($x)
{
    return $x;
}

function constant($x)
{
    return function () use ($x) {
        return $x;
    };
}

function lines($x)
{
    return explode("\n", $x);
}

function unlines(array $xs)
{
    return implode("\n", $xs);
}

function perLine($f, $xs)
{
    return unlines(array_map($f, lines($xs)));
}

function words($x)
{
    return explode(' ', $x);
}

function unwords(array $xs)
{
    return implode(' ', $xs);
}
