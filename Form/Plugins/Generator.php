<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Form\Environment;
use Olc\Widget\Widget;

class Generator extends Plugin
{
    public function __construct(Widget $widget)
    {
        parent::__construct();
        $this->name = 'Generator (' . get_class($widget) . ')';

        $this->addAdvice(
            array('render', 1, 'override'),
            self::getGenerator($widget)
        );
    }

    public static function getGenerator(Widget $widget)
    {
        $class = get_class();
        return function (Zipper $x) use ($widget, $class) {
            return $class::render($x, $widget);
        };
    }

    public static function render(Zipper $x, Widget $el)
    {
        $current = $x->getContent();
        list(, $value) = $x->getRoot()->get('environment')->get($x->getPath());

        $el->setAttributes($current->get());
        $el->set('name', $x->getPath());
        $el->set('value', $value);

        foreach ($current->getChildren() as $child) {
            $el->add($child->get('view'));
        }
        return $el;
    }
}
