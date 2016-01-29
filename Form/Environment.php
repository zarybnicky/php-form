<?php
namespace Olc\Form;

use Olc\Form\Types\DataSource;
use Olc\Form\Types\DataStatus;

class Environment
{
    protected $initial;
    protected $user;

    public function __construct($initial = array(), $user = array())
    {
        $this->initial = $initial;
        $this->user = $user;
    }

    public function get($name)
    {
        return isset($this->user[$name])
            ? array(DataStatus::USER(), $this->user[$name])
            : $this->getInitial($name);
    }

    public function set($name, $value)
    {
        $this->user[$name] = $value;
    }

    public function setInitial($name, $value)
    {
        $this->initial[$name] = $value;
    }

    public function getInitial($name)
    {
        return isset($this->initial[$name])
            ? array(DataStatus::INITIAL(), $this->initial[$name])
            : array(DataStatus::MISSING(), null);
    }
}
