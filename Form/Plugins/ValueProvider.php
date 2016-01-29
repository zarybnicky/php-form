<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;

class ValueProvider extends Plugin
{
    public function __construct()
    {
        $this->addAdvice(
            array('submit', 1, 'override'),
            array($this, 'fetchValue')
        );
    }

    public function fetchValue(Zipper $z)
    {
        list(, $value) = $z->getRoot()->get('environment')->get($z->getPath());
        return $value;
    }
}
