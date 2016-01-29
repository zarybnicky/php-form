<?php
namespace Olc\Widget;

class Widget
{
    protected $attributes = array();
    protected $children = array();

    protected $stylesheets = array();
    protected $scripts = array();

    public function __construct(array $attributes = array(), $child = array())
    {
        $this->name = get_called_class();
        $this->attributes = $attributes;

        //Flatten input
        $children = array();
        array_walk_recursive(
            (array_slice(func_get_args(), 1)),
            function ($a) use (&$children) {
                $children[] = $a;
            }
        );
        foreach ($children as $child) {
            $this->addChild($child);
        }
        $this->initialize();
    }

    public function initialize()
    {
    }

    public function get($name = null)
    {
        if ($name === null) {
            return $this->attributes;
        }
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    public function set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function setAttributes(array $new)
    {
        $this->attributes = array_merge($this->attributes, $new);
    }

    public function addChild($el, $n = null)
    {
        if ($n === null) {
            $this->children[] = $el;
        } else {
            array_splice($this->children, $n, 0, array($el));
        }
        if (!($el instanceof Widget)) {
            return;
        }
        if ($styles = $el->getStylesheets()) {
            $this->stylesheets = array_merge($this->stylesheets, $styles);
        }
        if ($scripts = $el->getScripts()) {
            $this->scripts = array_merge($this->scripts, $scripts);
        }
    }

    public function addStylesheet($attributes = array(), $source = '')
    {
        $this->stylesheets[] = array($attributes, $source);
    }

    public function addScript($attributes = array(), $source = '')
    {
        $this->scripts[] = array($attributes, $source);
    }

    public function getStylesheets()
    {
        return $this->stylesheets;
    }

    public function getScripts()
    {
        return $this->scripts;
    }

    public function render()
    {
        return '';
    }

    public function renderToString()
    {
        $result = '';

        foreach ($this->getStylesheets() as $style) {
            list($attrs, $src) = $style;
            $el = new Tag('style', $attrs, $src);
            $result .= $style->render();
        }
        $result .= $this->renderChild($this->render());
        foreach ($this->getScripts() as $script) {
            list($attrs, $src) = $script;
            $el = new Tag('script', $attrs, $src);
            $result .= $el->render();
        }

        return $result;
    }

    public function __toString()
    {
        return $this->renderToString();
    }

    protected function renderChildren()
    {
        $result = '';
        foreach ($this->children as $child) {
            $result .= $this->renderChild($child);
        }
        return $result;
    }

    protected function renderChild($element)
    {
        while ($element instanceof Widget) {
            $element = $element->render();
        }
        if (is_array($element)) {
            $result = '';
            foreach ($element as $child) {
                $result .= $this->renderChild($child);
            }
            return $result;
        } else {
            return (string) $element;
        }
    }
}
