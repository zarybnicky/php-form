<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Form\Environment;
use Olc\Form\Types\DataSource;


class EnvironmentEnricher extends Plugin
{
    public function __construct()
    {
        $set = array($this, 'setEnvironment');
        $unset = array($this, 'unsetEnvironment');

        $this->addAdvice(array('render', -100, 'before'), $set);
        $this->addAdvice(array('submit', -100, 'before'), $set);
        $this->addAdvice(array('render', -100, 'after'), $unset);
        $this->addAdvice(array('submit', -100, 'after'), $unset);
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
