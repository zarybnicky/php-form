<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;

class ErrorReducer extends Plugin
{
    public function renderBefore_50(Zipper $z)
    {
        $as = array(array());
        foreach ($z->getContent()->flattenPostorder() as $x) {
            if ($x->get('errors')) {
                $as[] = $x->get('errors');
            }
        }
        $z->getContent()->set('errors', call_user_func_array('array_merge', $as));
    }
}
