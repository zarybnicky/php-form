<?php

function get($prop, $x)
{
    return $x->$prop;
}

function isA($class, $x)
{
    return $x instanceof $class;
}

function call($x, $prop /* variadic */)
{
    $args = array_slice(func_get_args(), 2);
    return call_user_func_array(array($x, $prop), $args);
}
