<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;

class ValueReducer extends Plugin
{
    public function submitOverride1(Zipper $z)
    {
        $result = array();
        foreach ($z->getContent()->getChildren() as $k => $v) {
            $result[$k] = $v->get('result');
        }
        return $result;
    }
}
