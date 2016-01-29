<?php
include 'autoload.php';

class_exists('\\Olc\\Data\\Tree');
class_exists('\\Olc\\Data\\Zipper');

xdebug_start_trace('zipper');

function unfoldTree($fn, $seed)
{
    list($name, $value, $childSeeds) = $fn($seed);
    $tree = new Olc\Data\Tree($name, $value);
    foreach ($childSeeds as $seed) {
        $tree->add(unfoldTree($fn, $seed));
    }
    return $tree;
}

$t = unfoldTree(
    function ($n) {
        list($level, $x) = $n;
        return array(
            "node-$level-$x",
            $x,
            array_map(
                null,
                array_pad(array(), $level - 1, $level - 1),
                ($level - 2 >= 0) ? range(0, $level - 2) : array()
            )
        );
    },
    array(4, 0)
);

echo $t, "\n";
$z = new Olc\Data\Zipper($t);
echo $z;
$z->path('node-3-0.node-2-0.node-1-0');
echo $z;
$z->parent();
echo $z;
$z->nextSpace();
echo $z;
$z->insert(new Olc\Data\Tree('test', 0));
echo $z;
echo $t, "\n";
$z->delete();
echo $z;
echo $t, "\n";
$z->prevTree();
$z->next();
echo $z;
$z->prev();
echo $z;
echo '[', implode(', ', array_map(function ($x) {return $x->getName();}, $z->getForest())), "]\n";
$path = $z->getPath();
echo $path, "\n";
$z->root();
$z->path($path);
echo $z->getPath(), "\n";
$z->childByName('node-1-0');
echo $z;
$z->parent();
$z->lastChild();
echo $z;
