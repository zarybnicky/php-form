<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;

class ValueProvider extends Plugin
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'ValueProvider';

        $this->addAdvice(
            array('submit', 1, 'override'),
            get_class() . '::fetchValue'
        );
    }

    public static function fetchValue(Zipper $z)
    {
        list(, $value) = $z->getRoot()->get('environment')->get($z->getPath());
        return $value;
    }
}
