<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Form\Environment;
use Olc\Form\Types\DataSource;

class EnvironmentEnricher extends Plugin
{
    public function renderBefore_100(Zipper $z)
    {
        $this->setEnvironment($z);
    }

    public function renderAfter_100(Zipper $z)
    {
        $this->unsetEnvironment($z);
    }

    public function submitBefore_100(Zipper $z)
    {
        $this->setEnvironment($z);
    }

    public function submitAfter_100(Zipper $z)
    {
        $this->unsetEnvironment($z);
    }

    public function setEnvironment(Zipper $z)
    {
        $current = $z->getContent();

        $data = $this->getData($current->get('method'));
        $env = new Environment($current->get('initialData'), $data);
        $current->set('environment', $env);
    }

    public function unsetEnvironment(Zipper $z)
    {
        $current = $z->getContent();
        $current->set('environment', null);
    }

    public function getData(DataSource $s)
    {
        return $GLOBALS[$s->getValue()];
    }
}
