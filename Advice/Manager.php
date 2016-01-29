<?php
namespace Olc\Advice;

use InvalidArgumentException;
use Olc\Advice\Expression as E;

class Manager
{
    const BEFORE = 'before';
    const AFTER = 'after';
    const OVERRIDE = 'override';
    const AROUND = 'around';
    const BEFORE_WHILE = 'before-while';
    const BEFORE_UNTIL = 'before-until';
    const AFTER_WHILE = 'after-while';
    const AFTER_UNTIL = 'after-until';
    const FILTER_ARGS = 'filter-args';
    const FILTER_RETURN = 'filter-return';

    /**
`:before'	(lambda (&rest r) (apply FUNCTION r) (apply OLDFUN r))
`:after'	(lambda (&rest r) (prog1 (apply OLDFUN r) (apply FUNCTION r)))
`:around'	(lambda (&rest r) (apply FUNCTION OLDFUN r))
`:override'	(lambda (&rest r) (apply FUNCTION r))
`:before-while'	(lambda (&rest r) (and (apply FUNCTION r) (apply OLDFUN r)))
`:before-until'	(lambda (&rest r) (or  (apply FUNCTION r) (apply OLDFUN r)))
`:after-while'	(lambda (&rest r) (and (apply OLDFUN r) (apply FUNCTION r)))
`:after-until'	(lambda (&rest r) (or  (apply OLDFUN r) (apply FUNCTION r)))
`:filter-args'	(lambda (&rest r) (apply OLDFUN (funcall FUNCTION r)))
`:filter-return'(lambda (&rest r) (funcall FUNCTION (apply OLDFUN r)))
     */

    private $advice = array();
    private $specAlias = array();

    public function registerAdviceTarget($spec)
    {
        $this->advice[$spec] = array();
    }

    public function registerAdviceTargets($specs)
    {
        $this->advice = array_merge(
            $this->advice,
            array_combine($specs, array_fill(0, count($specs), array()))
        );
    }

    public function run($spec, $fn, $args)
    {
        list($target, $depth, $type, $name) = $this->parseSpec(array($spec));

        $advice = $this->advice[$target];
        if (!$advice) {
            return call_user_func_array($fn, $args);
        }

        $expr = $this->buildExpression($fn, $advice);

        /*
        foreach ($advice as $a) {
            list($type, $f, $name, $depth) = $a;
            echo "$target($depth):$type@$name\n";
        }
        */
        //echo $expr, "\n";

        return $expr->immediate($args);
    }

    private function buildExpression($fn, $advice)
    {
        //Stable sort by depth
        array_walk($advice, function (&$v, $k) { $v = array(-$v[3], $k, $v); });
        asort($advice);
        array_walk($advice, function (&$v, $k) { $v = $v[2]; });

        $expr = new E\Call($fn);
        foreach ($advice as $a) {
            list($type, $adviceFn) = $a;
            $expr = $this->adviceStep($expr, $type, new E\Call($adviceFn));
        }
        return $expr;
    }

    private function adviceStep($fn, $type, $advice)
    {
        switch ($type) {
        case self::BEFORE:
            return new E\Prog2($advice, $fn);
        case self::AFTER:
            return new E\Prog1($fn, $advice);
        case self::AROUND:
            return new E\Apply($advice, new E\Force($fn), new E\Rest());
        case self::OVERRIDE:
            return $advice;
        case self::BEFORE_WHILE:
            return new E\AndF($advice, $fn);
        case self::BEFORE_UNTIL:
            return new E\OrF($advice, $fn);
        case self::AFTER_WHILE:
            return new E\AndF($fn, $advice);
        case self::AFTER_UNTIL:
            return new E\OrF($fn, $advice);
        case self::FILTER_ARGS:
            return new E\Apply($fn, new E\Apply($advice, new E\Collect(new E\Rest())));
        case self::FILTER_RETURN:
            return new E\Apply($advice, new E\Collect($fn));
        }
    }

    public function on($spec, $callable)
    {
        list($target, $depth, $type, $name) = $this->parseSpec($spec);
        if (!is_callable($callable)) {
            throw new InvalidArgumentException('Advice function must be callable');
        }
        if (!$type) {
            throw new InvalidArgumentException('Adding advice requires a type');
        }
        if ($depth === null) {
            $depth = 0;
        }
        $this->advice[$target][] = array($type, $callable, $name, $depth);
    }

    public function uses($spec)
    {
        list($testTarget, $testDepth, $testType, $testName) = $this->parseSpec($spec);
        foreach ($this->advice[$testTarget] as $advice) {
            list($type, $fn, $name, $depth) = $advice;
            if ($testName == $name && $testType == $type && $testDepth == $depth) {
                return true;
            }
        }
        return false;
    }

    public function off($spec, $callable = null)
    {
        list($target, $depth, $type, $name) = $this->parseSpec($spec);
        if ($target && $depth === null && $type === null && $name === null && $callable === null) {
            $this->advice[$target] = array();
            return;
        }

        $filter = array();
        if ($type !== null) {
            $filter[0] = $type;
        }
        if ($callable !== null) {
            $filter[1] = $callable;
        }
        if ($name !== null) {
            $filter[2] = $name;
        }
        if ($depth !== null) {
            $filter[3] = $depth;
        }

        foreach ($this->advice[$target] as $key => $advice) {
            foreach ($filter as $fKey => $fValue) {
                if ($advice[$fKey] !== $fValue) {
                    continue 2;
                }
            }
            unset($this->advice[$target][$key]);
        }
        $this->advice[$target] = array_values($this->advice[$target]);
    }

    private function parseSpec($spec)
    {
        static $adviceTypes = array(
            self::BEFORE,
            self::AFTER,
            self::AROUND,
            self::OVERRIDE,
            self::BEFORE_WHILE,
            self::BEFORE_UNTIL,
            self::AFTER_WHILE,
            self::AFTER_UNTIL,
            self::FILTER_ARGS,
            self::FILTER_RETURN
        );
        list($target, $depth, $type, $name) = array_pad($spec, 4, null);

        if (!isset($this->advice[$target])) {
            throw new InvalidArgumentException("Invalid advice target '$target'");
        }

        if ($type && !in_array($type, $adviceTypes)) {
            throw new InvalidArgumentException("Invalid advice type '$type'");
        }

        if ($depth !== null) {
            if ($depth === '') {
                $depth = null;
            } else {
                $depth = intval($depth);
            }
        }

        return array($target, $depth, $type, $name);
    }
}
