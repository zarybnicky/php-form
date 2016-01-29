<?php
include 'autoload.php';

class_exists('\\Olc\\Advice\\Proxy');
class_exists('\\Olc\\Advice\\Bounce');
class_exists('\\Olc\\Advice\\Expression\\Base');
class_exists('\\Olc\\Advice\\Expression\\Prog1');
class_exists('\\Olc\\Advice\\Expression\\Prog2');
class_exists('\\Olc\\Advice\\Expression\\Call');
class_exists('\\Olc\\Advice\\Expression\\AndF');
class_exists('\\Olc\\Advice\\Expression\\OrF');
class_exists('\\Olc\\Advice\\Expression\\Apply');
class_exists('\\Olc\\Advice\\Expression\\Force');
class_exists('\\Olc\\Advice\\Expression\\Rest');
class_exists('\\Olc\\Advice\\Expression\\Collect');

class Test
{
    public function multiply($x, $y)
    {
        return $x * $y;
    }
}

xdebug_start_trace('proxy');

$o = new Olc\Advice\Proxy(new Test());
$o->on('multiply:override', function ($x, $y) {return $x + $y;});
$o->on('multiply:after', function () {echo "after\n";});
$o->on('multiply:before', function () {echo "before override\n";});
$o->on('multiply:around', function ($f, $x, $y) {return $f($x, $y) . $f($x + 1, $y);});
$o->on('multiply:filter-return', function ($x) {return $x + 1;});
$o->on('multiply:filter-args', function ($xs) {return array(1, 2);});
$o->on('multiply:after-until', function ($x, $y) {return 1;});

echo $o->multiply(5, 3);
