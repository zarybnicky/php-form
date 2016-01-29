<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Form\Types\Enctype;

class EnctypeReducer extends Plugin
{
    public function __construct()
    {
        $this->addAdvice(
            array('render', -50, 'before'),
            array($this, 'extractEnctype')
        );
    }

    public function extractEnctype(Zipper $z)
    {
        $as = array();
        foreach ($z->getContent()->flattenPostorder() as $x) {
            $as[] = $x->get('enctype');
        }
        $z->getContent()->set('enctype', Enctype::getMonoid()->mconcat($as));
    }
}
