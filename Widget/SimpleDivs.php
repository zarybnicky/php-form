<?php
namespace Olc\Widget;

class SimpleDivs extends Widget
{

    public function render()
    {
        $children = array_map(
            function ($x) {
                $x->set('placeholder', $x->get('label'));
                return new Tag('div', array(), $x);
            },
            $this->children
        );
        $submit = '';
        if (!isset($this->value['submit']) || $this->value['submit']) {
            $submit = new Tag('input', array('type' => 'submit'));
        }
        return new Tag(
            'div',
            $this->value,
            $children,
            $submit
        );
    }
}
