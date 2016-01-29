<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Widget\Tag;

class Wrapper extends Plugin
{
    public function renderAround_10($f, Zipper $z)
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

    public function submitBeforeWhile_10(Zipper $z)
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
