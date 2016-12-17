<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Form\Environment;
use Olc\Form\Types\DataSource;


class EnvironmentEnricher extends Plugin
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'EnvironmentEnricher';

        $set = get_class() . '::setEnvironment';
        $unset = get_class() . '::unsetEnvironment';

        $this->addAdvice(array('render', -100, 'before'), $set);
        $this->addAdvice(array('submit', -100, 'before'), $set);
        $this->addAdvice(array('render', -100, 'after'), $unset);
        $this->addAdvice(array('submit', -100, 'after'), $unset);
    }

    public static function setEnvironment(Zipper $z)
    {
        $current = $z->getContent();

        $source = $current->get('method');
        $data = $GLOBALS[$source->getValue()];
        $env = new Environment($current->get('initialData'), $data);
        $current->set('environment', $env);
    }

    public static function unsetEnvironment(Zipper $z)
    {
        $current = $z->getContent();
        $current->set('environment', null);
    }
}
