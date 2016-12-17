<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Widget\Tag;

class Wrapper extends Plugin
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Wrapper';

        $this->addAdvice(
            array('render', -10, 'around'),
            get_class() . '::renderForm'
        );
        $this->addAdvice(
            array('submit', -10, 'before-while'),
            get_class() . '::shouldFormSubmit'
        );
    }

    public static function renderForm($f, Zipper $z)
    {
        $current = $z->getContent();
        return new Tag(
            'form',
            $current->get(),
            $f($z),
            new Tag(
                'input',
                array(
                    'type' => 'hidden',
                    'name' => $z->getPath() . '/id',
                    'value' => $current->getName()
                )
            )
        );
    }

    public static function shouldFormSubmit(Zipper $z)
    {
        $current = $z->getContent();

        list(, $value) = $current->get('environment')->get($z->getPath() . '/id');
        if ($_SERVER['REQUEST_METHOD'] == $current->get('method')->getName()
            && $value == $current->getName()
        ) {
            //Continue
            return true;
        } else {
            //Abort
            return null;
        }
    }
}
