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
        $submit = null;
        if (!isset($this->attributes['submit']) || $this->attributes['submit']) {
            $submit = new Tag('input', array('type' => 'submit'));
        }
        return new Tag(
            'div',
            $this->attributes,
            $children,
            $submit
        );
    }
}
