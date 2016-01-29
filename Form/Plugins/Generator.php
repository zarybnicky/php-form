<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Form\Environment;
use Olc\Widget\Widget;

class Generator extends Plugin
{
    protected $widget;

    public function __construct(Widget $x)
    {
        parent::__construct();
        $this->widget = $x;
    }

    public function renderOverride1(Zipper $x)
    {
        $el = $this->widget;
        $current = $x->getContent();
        list(, $value) = $x->getRoot()->get('environment')->get($x->getPath());

        $el->setAttributes($current->get());
        $el->set('name', $x->getPath());
        $el->set('value', $value);

        foreach ($current->getChildren() as $child) {
            $el->addChild($child->get('view'));
        }
        return $el;
    }

    public function getName()
    {
        return 'generator-' . get_class($this->widget);
    }
}
