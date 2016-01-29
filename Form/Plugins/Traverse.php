<?php
namespace Olc\Form\Plugins;

use Olc\Advice\Bounce;
use Olc\Data\Zipper;

class Traverse extends Plugin
{
    public function __construct()
    {
        $this->addAdvice(
            array('render', 0, 'before'),
            array($this, 'traverseRender')
        );
        $this->addAdvice(
            array('submit', 0, 'before'),
            array($this, 'traverseSubmit')
        );
    }

    public function traverseRender(Zipper $z)
    {
        return $this->traverse('render', $z);
    }

    public function traverseSubmit(Zipper $z)
    {
        return $this->traverse('submit', $z);
    }

    protected function traverse($method, $z)
    {
        if ($z->isLeaf()) {
            return;
        }
        $z->firstChild();

        $recur = array($this, 'recur');
        return new Bounce(
            function () use ($method, $z) {
                $z->getContent()->$method($z);
            },
            function () use ($recur, $method, $z) {
                return call_user_func($recur, $method, $z);
            }
        );
    }

    public function recur($method, $z)
    {
        if (!$z->next()) {
            $z->parent();
            return null;
        }
        $recur = array($this, 'recur');
        return new Bounce(
            function () use ($method, $z) {
                $z->getContent()->$method($z);
            },
            function () use ($method, $z, $recur) {
                return call_user_func($recur, $method, $z);
            }
        );
    }
}
