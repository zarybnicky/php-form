<?php
namespace Olc\Widget;

class SimpleTable extends Widget
{
    public function render()
    {
        $errors = array();
        $children = array_map(
            function ($x) use (&$errors) {
                if ($x->get('errors')) {
                    $errors = array_merge($errors, $x->get('errors'));
                }
                if ($label = $x->get('label')) {
                    $label = new Tag('label', array('for' => $x->get('name')), $label);
                }
                return new Tag(
                    'tr',
                    array(),
                    new Tag('td', array(), $label),
                    new Tag('td', array(), $x)
                );
            },
            $this->children
        );
        return array(
            new Tag(
                'table', $this->attributes,
                $children,
                new Tag(
                    'tr', array(),
                    new Tag('td'),
                    new Tag('td', array(), new Tag('input', array('type'=>'submit')))
                )
            )
        );
    }
}