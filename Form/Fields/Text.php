<?php
namespace Olc\Form\Fields;

use Olc\Form\Form;
use Olc\Form\Plugins\Generator;
use Olc\Form\Plugins\ValueProvider;
use Olc\Widget\Tag;

class Text extends Form
{
    public function initialize()
    {
        $this->with(new Generator(new Tag('input', array('type' => 'text'))));
        $this->with(new ValueProvider());
    }
}
