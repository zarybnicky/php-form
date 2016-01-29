<?php
namespace Olc\Data;

use LogicException;

/**
 * A data structure for working with locations in trees or forests (a zipper)
 *
 * @see http://hackage.haskell.org/package/rosezipper-0.2
 */
class Zipper
{
    protected $full = false;
    protected $content = null;
    protected $before = array();
    protected $after = array();
    protected $parents = array();

    public function __construct(Tree $t)
    {
        $this->full = true;
        $this->content = $t;
    }

    public function __toString()
    {
        return "[\n"
            . "  content: " . ($this->content ? $this->content->getName() : '(empty)') . "\n"
            . "  before: [" . implode(', ', array_map(function ($x) {return $x->getName();}, $this->before)) . "]\n"
            . "  after: [" . implode(', ', array_map(function ($x) {return $x->getName();}, $this->after)) . "]\n"
            . "  parents: [" . implode(', ', array_map(function ($x) {return $x[1]->getName();}, $this->parents)) . "]\n"
            . "]\n";
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getBefore()
    {
        return $this->before;
    }

    public function getAfter()
    {
        return $this->after;
    }

    public function getParents()
    {
        return $this->parents;
    }

    public function getRoot()
    {
        $root = end($this->parents) ?: array(null, $this->content, null);
        return $root[1];
    }

    public function getParent()
    {
        $parent = reset($this->parents) ?: array(null, null, null);;
        return $parent[1];
    }

    public function getForest()
    {
        return $this->full
            ? array_merge(array_reverse($this->before), array($this->content), $this->after)
            : array_merge(array_reverse($this->before), $this->after);
    }

    public function getPath()
    {
        if (!$this->full) throw new LogicException();
        $path = $this->content->getName();
        foreach ($this->parents as $parent) {
            $path = $parent[1]->getName() . '/' . $path;
        }
        return $path;
    }

    public function prev()
    {
        if ($this->full) {
            $this->prevSpace();
            return $this->prevTree();
        } else {
            return $this->prevTree() ? $this->prevSpace() : false;
        }
    }

    public function next()
    {
        if ($this->full) {
            $this->nextSpace();
            return $this->nextTree();
        } else {
            return $this->nextTree() ? $this->nextSpace() : false;
        }
    }

    public function prevSpace()
    {
        if (!$this->full) throw new LogicException();
        $this->full = false;
        array_splice($this->after, 0, 0, array($this->content->getName() => $this->content));
        $this->content = null;
    }

    public function nextSpace()
    {
        if (!$this->full) throw new LogicException();
        $this->full = false;
        array_splice($this->before, 0, 0, array($this->content->getName() => $this->content));
        $this->content = null;
    }

    public function prevTree()
    {
        if ($this->full) throw new LogicException();
        if (!$this->before) {
            return false;
        }
        $this->full = true;
        $this->content = array_shift($this->before);
        return true;
    }

    public function nextTree()
    {
        if ($this->full) throw new LogicException();
        if (!$this->after) {
            return false;
        }
        $this->full = true;
        $this->content = array_shift($this->after);
        return true;
    }

    public function parent()
    {
        if (!$this->parents) {
            return false;
        }
        $this->full = true;
        list($this->before, $this->content, $this->after) = array_shift($this->parents);
        return true;
    }

    public function root()
    {
        while ($this->parent()) {
            ;
        }
    }

    public function children()
    {
        if (!$this->full) throw new LogicException();
        array_unshift($this->parents, array($this->before, $this->content, $this->after));
        $this->full = false;
        $this->after = $this->content->getChildren();
        $this->content = null;
        $this->before = array();
    }

    public function firstSpace()
    {
        if ($this->full) throw new LogicException();
        $this->after = array_merge(array_reverse($this->before), $this->after);
        $this->before = array();
    }

    public function lastSpace()
    {
        if ($this->full) throw new LogicException();
        $this->before = array_merge($this->before, array_reverse($this->after));
        $this->after = array();
    }

    public function spaceAt($n)
    {
        if ($this->full) throw new LogicException();
        $forest = $this->getForest();
        $this->before = array_slice($forest, 0, $n);
        $this->after = array_slice($forest, $n);
    }

    public function firstChild()
    {
        if (!$this->full) throw new LogicException();
        $this->children();
        return $this->nextTree();
    }

    public function lastChild()
    {
        if (!$this->full) throw new LogicException();
        $this->children();
        $this->lastSpace();
        return $this->prevTree();
    }

    public function childAt($n)
    {
        if (!$this->full) throw new LogicException();
        $this->children();
        $this->spaceAt($n);
        return ($n < 0) ? false : $this->nextTree();
    }

    public function childByName($name)
    {
        $children = $this->content->getChildren();
        $n = array_search($name, array_keys($children));
        return $this->childAt($n);
    }

    public function path($path)
    {
        if (!$this->full) throw new LogicException();
        $path = explode('/', $path);
        if ($path[0] == $this->getContent()->getName()) {
            array_shift($path);
        }
        foreach ($path as $name) {
            if (!$this->childByName($name)) {
                return false;
            }
        }
        return true;
    }

    public function isRoot()
    {
        return (bool) $this->parents;
    }

    public function isFirst()
    {
        return (bool) $this->before;
    }

    public function isLast()
    {
        return (bool) $this->after;
    }

    public function isLeaf()
    {
        if (!$this->full) throw new LogicException();
        return !$this->content->getChildren();
    }

    public function insert(Tree $x)
    {
        if ($this->full) throw new LogicException();
        $n = count($this->before);
        $this->parent();
        $this->content->addAt($n, $x);
        $this->childAt($n);
    }

    public function delete()
    {
        if (!$this->full) throw new LogicException();
        $n = count($this->before);
        $this->parent();
        $this->content->removeAt($n);
        $this->children();
        $this->spaceAt($n);
    }

    public function setTree(Tree $x)
    {
        if (!$this->full) throw new LogicException();
        $this->delete();
        $this->insert($x);
    }

    public function modifyTree($fn)
    {
        if (!$this->full) throw new LogicException();
        return $this->setTree($fn($this->content));
    }

    public function traverse($fn)
    {
        while (!$this->isLeaf()) {
            $this->lastChild();
        }
        do {
            do {
                $fn($this);
            } while ($this->prev());
        } while ($this->parent());
        $this->nextTree();
    }
}
