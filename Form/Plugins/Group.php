<?php
namespace Olc\Form\Plugins;

use Olc\Advice\Manager;
use Olc\Data\Zipper;

abstract class Group extends Plugin
{
    protected $plugins;

    public function __construct()
    {
        parent::__construct();
        $this->name = 'Group';
    }

    public function addPlugin(Plugin $p)
    {
        $this->name .= '-' . $p->getName();
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
}
