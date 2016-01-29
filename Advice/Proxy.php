<?php
namespace Olc\Advice;

use ReflectionObject;

class Proxy extends Manager
{
    private $target;
    private $reflection;

    public function __construct($target)
    {
        $this->target = $target;
        $this->targetClass = get_class($target);
        $this->reflection = new ReflectionObject($target);
        $methods = array();
        foreach ($this->reflection->getMethods() as $method) {
            $methods[] = $method->name;
        }
        $this->registerAdviceTargets($methods);
    }

    public function __call($name, $args)
    {
        $target = $this->target;
        $backtrace = debug_backtrace(false);
        $caller = isset($backtrace[2]) ? $backtrace[2] : null;
        $callerClass = isset($caller['class']) ? $caller['class'] : null;

        if ($callerClass != $this->targetClass) {
            return $this->run($name, array($target, $name), $args);
        } else {
            $m = $this->reflection->getMethod($name);
            return $this->run(
                $name,
                function () use ($m, $target) {
                    $m->setAccessible(true);
                    return $m->invokeArgs($target, func_get_args());
                },
                $args
            );
        }
    }
}
