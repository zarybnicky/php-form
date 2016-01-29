<?php
namespace Olc\Form;

use InvalidArgumentException;
use Olc\Advice\Manager;
use Olc\Data\Tree;
use Olc\Data\Zipper;
use Olc\Form\Plugins\Plugin;
use Olc\Form\Plugins\TopLevel;

abstract class Form extends Tree
{
    protected $proxy;
    protected $plugins = array();
    protected $attributes = array();

    public function __construct($options = array())
    {
        if (is_string($options)) {
            $options = array('label' => $options);
        }
        if (!is_array($options)) {
            throw new InvalidArgumentException(
                'Invalid options argument given to ' . get_called_class()
            );
        }
        if (!isset($options['label'])) {
            $options['label'] = md5(uniqid(mt_rand(), true));
        }

        parent::__construct(self::makeId($options['label']), $this);
        $this->attributes = $options;

        $this->proxy = new Manager();
        $this->proxy->registerAdviceTarget('submit');
        $this->proxy->registerAdviceTarget('render');

        $this->initialize();
        $this->sanityCheck();
    }

    abstract protected function initialize();

    private function sanityCheck()
    {
    }

    public function run()
    {
        $topLevel = new TopLevel();
        $this->with($topLevel);

        $z = new Zipper($this);
        $this->submit($z);
        $this->render($z);

        $this->without($topLevel);
    }

    public function get($name = null)
    {
        if ($name === null) {
            return $this->attributes;
        }
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    public function set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function submit(Zipper $z)
    {
        $result = $this->proxy->run('submit', $this->always(null), array($z));
        $this->set('result', $result);
        return $result;
    }

    public function render(Zipper $z)
    {
        $view = $this->proxy->run('render', $this->always(null), array($z));
        $this->set('view', $view);
        return $view;
    }

    public function with(Plugin $p)
    {
        if (array_search($p->getName(), $this->plugins) === false) {
            $this->plugins[] = $p->getName();
            $p->load($this->proxy);
        }
        return $this;
    }

    public function without(Plugin $p)
    {
        if (($key = array_search($p->getName(), $this->plugins)) !== false) {
            unset($this->plugins[$key]);
        }
        $p->unload($this->proxy);
        return $this;
    }

    private function always($x)
    {
        return function () use ($x) {
            return $x;
        };
    }

    private function makeId($name)
    {
        return str_replace(
            array('.', ',', ' '),
            '-',
            strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name))
        );
    }

    public function __toString()
    {
        $children = '';
        foreach ($this->getChildren() as $child) {
            $children .= "\n" . implode(
                "\n", array_map(
                    function ($x) {
                        return '  ' . $x;
                    },
                    explode("\n", (string) $child)
                )
            );
        }
        return "{$this->name} (" . implode(', ', $this->plugins) . ")$children";
    }
}
