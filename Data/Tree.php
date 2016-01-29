<?php
namespace Olc\Data;

use LogicException;

class Tree
{
    protected $name;
    protected $value;
    protected $children;

    public function __construct($name, $value, $children = array())
    {
        $this->name = $name;
        $this->value = $value;
        $this->children = $children;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function find($name)
    {
        return isset($this->children[$name]) ? $this->children[$name] : false;
    }

    public function contains(Tree $element)
    {
        return isset($this->children[$element->getName()]);
    }

    public function add(Tree $element)
    {
        if ($this->contains($element)) {
            throw new LogicException('Attemped to redefine child ' . $element->getName());
        }
        $this->children[$element->getName()] = $element;
    }

    public function remove(Tree $element)
    {
        if (!$this->contains($element)) {
            throw new LogicException('Attempted to remove nonexistent child ' . $element->getName());
        }
        unset($this->children[$element->getName()]);
    }

    public function addAt($n, Tree $element)
    {
        if ($this->contains($element)) {
            throw new LogicException('Attempted to redefine child ' . $element->getName());
        }
        array_splice($this->children, $n, 0, array($element->getName() => $element));
    }

    public function removeAt($n)
    {
        array_splice($this->children, $n, 1);
    }

    public function update($fn)
    {
        $this->value = $fn($this->value);
    }

    public function traverse($fn)
    {
        $stack = array($this);
        while ($stack) {
            $current = array_pop($stack);
            $current->update($fn);
            $stack = array_merge($stack, array_reverse($current->getChildren()));
        }
    }

    public function flattenPostorder()
    {
        $result = array();
        $stack = array($this);
        $ancestors = array();
        while ($stack) {
            $x = end($stack);
            if ($children = $x->getChildren()) {
                if ($x !== end($ancestors)) {
                    $ancestors[] = $x;
                    $stack = array_merge($stack, array_reverse($children));
                    continue;
                }
                array_pop($ancestors);
            }
            $result[$x->getName()] = $x->getValue();
            array_pop($stack);
        }
        return $result;
    }

    public function __toString()
    {
        $children = '';
        foreach ($this->getChildren() as $child) {
            $children .= "\n" . implode(
                "\n", array_map(
                    function ($x) {
                        return '  ' . $x;
                    },
                    explode("\n", (string) $child)
                )
            );
        }
        return "{$this->name} (" . gettype($this->value) . ")$children";
    }
}
