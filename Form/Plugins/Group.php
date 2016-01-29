<?php
namespace Olc\Form\Plugins;

use Olc\Advice\Manager;
use Olc\Data\Zipper;

abstract class Group extends Plugin
{
    protected $plugins;

    public function addPlugin(Plugin $p)
    {
        $this->plugins[] = $p;
    }

    public function load(Manager $p)
    {
        foreach ($this->plugins as $plugin) {
            $plugin->load($p);
        }
    }

    public function unload(Manager $p)
    {
        foreach ($this->plugins as $plugin) {
            $plugin->unload($p);
        }
    }

    public function getName()
    {
        return 'group-' . implode(
            '-',
            array_map(
                function ($x) {
                    return $x->getName();
                },
                $this->plugins
            )
        );
    }
}
