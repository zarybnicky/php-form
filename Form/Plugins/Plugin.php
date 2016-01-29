<?php
namespace Olc\Form\Plugins;

use LogicException;
use Olc\Advice\Manager;

abstract class Plugin
{
    protected $advice = array();

    protected function addAdvice($spec, $fn)
    {
        $spec = array_pad($spec, 4, null);
        if (!$spec[3]) {
            $spec[3] = $this->getName();
        }
        $this->advice[] = array($spec, $fn);
    }

    public function load(Manager $p)
    {
        foreach ($this->advice as $x) {
            list($spec, $fn) = $x;
            if (!$p->uses($spec)) {
                $p->on($spec, $fn);
            }
        }
    }

    public function unload(Manager $p)
    {
        foreach ($this->advice as $x) {
            list($spec, $fn) = $x;
            $p->off($spec, $fn);
        }
    }

    public function getName()
    {
        return get_called_class();
    }
}
