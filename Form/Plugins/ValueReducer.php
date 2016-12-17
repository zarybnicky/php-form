<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;

class ValueReducer extends Plugin
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'ValueReducer';

        $this->addAdvice(
            array('submit', 1, 'override'),
            get_class() . '::reduce'
        );
    }

    public static function reduce(Zipper $z)
    {
        $result = array();
        foreach ($z->getContent()->getChildren() as $k => $v) {
            $result[$k] = $v->get('result');
        }
        return $result;
    }
}
