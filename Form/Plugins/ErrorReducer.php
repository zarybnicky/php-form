<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;

class ErrorReducer extends Plugin
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'ErrorReducer';

        $this->addAdvice(
            array('render', -50, 'before'),
            get_class() . '::extractErrors'
        );
    }

    public static function extractErrors(Zipper $z)
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
