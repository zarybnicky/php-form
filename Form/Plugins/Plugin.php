<?php
namespace Olc\Form\Plugins;

use LogicException;
use Olc\Advice\Manager;

abstract class Plugin
{
    private static $cache = array();
    protected $methods = array();

    public function __construct()
    {
        $class = get_class($this);
        if (!isset(self::$cache[$class])) {
            $methods = array();
            foreach (get_class_methods($this) as $fn) {
                $target = substr($fn, 0, 6);
                if ($target == 'render' || $target === 'submit') {
                    $methods[] = $this->parseMethod($fn);
                }
            }
            self::$cache[$class] = $methods;
        }
        $this->methods = self::$cache[$class];
    }

    protected function parseMethod($fn)
    {
        if (!preg_match('/(render|submit)(.*?)(_?[0-9]+|)$/', $fn, $match)) {
            throw new LogicException("Unrecognized plugin method '$fn'");
        }
        list(, $target, $type, $depth) = $match;
        $type = ltrim(strtolower(preg_replace('/[A-Z]/', '-$0', $type)), '-');
        $depth = $depth ? str_replace('_', '-', $depth) : 0;
        $name = $this->getName();

        return array(array($target, $depth, $type, $name), $fn);
    }

    public function load(Manager $p)
    {
        foreach ($this->methods as $x) {
            list($spec, $fn) = $x;
            if (!$p->uses($spec)) {
                $p->on($spec, array($this, $fn));
            }
        }
    }

    public function unload(Manager $p)
    {
        foreach ($this->methods as $x) {
            list($spec, $fn) = $x;
            $p->off($spec, array($this, $fn));
        }
    }

    public function getName()
    {
        return get_called_class();
    }
}
