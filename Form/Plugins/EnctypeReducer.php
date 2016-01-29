<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Form\Types\Enctype;

class EnctypeReducer extends Plugin
{
    public function renderBefore_50(Zipper $z)
    {
        $as = array();
        foreach ($z->getContent()->flattenPostorder() as $x) {
            $as[] = $x->get('enctype');
        }
        $z->getContent()->set('enctype', Enctype::getMonoid()->mconcat($as));
    }
}
