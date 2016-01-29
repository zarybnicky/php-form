<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;

class ValueReducer extends Plugin
{
    public function __construct()
    {
        $this->addAdvice(
            array('submit', 1, 'override'),
            array($this, 'reduce')
        );
    }

    public function reduce(Zipper $z)
    {
        $result = array();
        foreach ($z->getContent()->getChildren() as $k => $v) {
            $result[$k] = $v->get('result');
        }
        return $result;
    }
}
