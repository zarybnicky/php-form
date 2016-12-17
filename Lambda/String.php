<?php
function concat($xs)
{
    if (all($xs, 'is_string')) {
        return $xs
            ? implode('', $xs)
            : '';
    } elseif (all($xs, 'is_array')) {
        return $xs
            ? call_user_func_array('array_merge', $xs)
            : array();
    }
    var_dump($xs);
    fail();
}

function contains($x, $token, $strict = true)
{
    if (is_string($x)) {
        return ($strict ? strpos($x, $token) : stripos($x, $token)) !== false;
    }
    return any($x, __::equals($token, $strict));
}

function startsWith($x, $token, $strict = true)
{
    return ($strict ? strpos($x, $token) : stripos($x, $token)) === 0;
}

function endsWith($x, $token, $strict = true)
{
    return ($strict ? strrpos($x, $token) : strripos($x, $token))
        === strlen($x) - strlen($token);
}

function equals($x, $y, $strict = true)
{
    return $strict ? ($x === $y) : ($x == $y);
}
