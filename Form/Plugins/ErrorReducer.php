<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;

class ErrorReducer extends Plugin
{
    public function __construct()
    {
        $this->addAdvice(
            array('render', -50, 'before'),
            array($this, 'extractErrors')
        );
    }

    public function extractErrors(Zipper $z)
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
