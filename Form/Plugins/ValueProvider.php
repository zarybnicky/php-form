<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;

class ValueProvider extends Plugin
{
    public function submitOverride1(Zipper $z)
    {
        list(, $value) = $z->getRoot()->get('environment')->get($z->getPath());
        return $value;
    }
}
