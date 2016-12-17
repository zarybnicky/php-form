<?php
namespace Olc\Form\Plugins;

use Olc\Advice\Bounce;
use Olc\Data\Zipper;

class Traverse extends Plugin
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Traverse';

        $this->addAdvice(
            array('render', 0, 'before'),
            get_class() . '::traverseRender'
        );
        $this->addAdvice(
            array('submit', 0, 'before'),
            get_class() . '::traverseSubmit'
        );
    }

    public static function traverseRender(Zipper $z)
    {
        return self::traverse('render', $z);
    }

    public static function traverseSubmit(Zipper $z)
    {
        return self::traverse('submit', $z);
    }

    public static function traverse($method, $z)
    {
        if ($z->isLeaf()) {
            return;
        }
        $z->firstChild();
        return self::getStep($method, $z);
    }

    public static function recur($method, $z)
    {
        if (!$z->next()) {
            $z->parent();
            return null;
        }
        return self::getStep($method, $z);
    }

    public static function getStep($method, $z)
    {
        $class = get_class();
        return new Bounce(
            function () use ($method, $z) {
                $z->getContent()->$method($z);
            },
            function () use ($class, $method, $z) {
                return $class::recur($method, $z);
            }
        );
    }
}
