<?php
namespace Olc\Data;

use InvalidArgumentException;
use LogicException;
use ReflectionClass;

abstract class Enum
{
    private $name;
    private $value;
    private $index;

    private static $constants = array();
    private static $instances = array();

    final private function __construct($name, $value, $index)
    {
        $this->name = $name;
        $this->value = $value;
        $this->index = $index;
    }

    public function __toString()
    {
        return $this->name;
    }

    final public function getValue()
    {
        return $this->value;
    }

    final public function getName()
    {
        return $this->name;
    }

    final public function getIndex()
    {
        return $this->index;
    }

    final public function is($enumerator)
    {
        return $this === $enumerator || $this->value === $enumerator;
    }

    final public static function has($value)
    {
        if ($value instanceof static) {
            return true;
        }
        return in_array($value, self::getConstants(), true);
    }

    final public static function __callStatic($method, $args)
    {
        return self::getByName($method);
    }

    final public static function get($value)
    {
        if ($value instanceof static) {
            return $value;
        }

        $class = get_called_class();
        $constants = self::getConstants();

        $name = array_search($value, $constants, true);
        if ($name === false) {
            $message = is_scalar($value)
                ? 'Unknown value ' . var_export($value, true)
                : 'Invalid value of type ' . (is_object($value) ? get_class($value) : gettype($value));
            throw new InvalidArgumentException($message);
        }

        if (!isset(self::$instances[$class][$name])) {
            $index = array_search($value, array_values($constants), true);
            self::$instances[$class][$name] = new $class($name, $value, $index);
        }

        return self::$instances[$class][$name];
    }

    final public static function getByName($name)
    {
        $name = (string) $name;
        $class = get_called_class();
        $constants = self::getConstants();

        if (!isset($constants[$name])) {
            throw new InvalidArgumentException($const . ' not defined');
        }

        if (!isset(self::$instances[$class][$name])) {
            $value = $constants[$name];
            $index = array_search($value, array_values($constants), true);
            self::$instances[$class][$name] = new $class($name, $value, $index);
        }
        return self::$instances[$class][$name];
    }

    final public static function getByIndex($index)
    {
        $index = (int) $index;
        $class = get_called_class();
        $constants = self::getConstants();

        $names = array_keys($constants);
        if (!isset($names[$index])) {
            throw new InvalidArgumentException(
                'Invalid index number, must between 0 and ' . count($constants) - 1
            );
        }

        $name = $names[$index];
        if (!isset(self::$instances[$class][$name])) {
            $value = $constants[$name];
            self::$instances[$class][$name] = new $class($name, $value, $index);
        }
        return self::$instances[$class][$name];
    }

    final public static function getConstants()
    {
        $class = get_called_class();
        if (isset(self::$constants[$class])) {
            return self::$constants[$class];
        }

        $reflection = new ReflectionClass($class);
        $constants = $reflection->getConstants();

        $ambiguous = array();
        foreach ($constants as $value) {
            $names = array_keys($constants, $value, true);
            if (count($names) > 1) {
                $ambiguous[var_export($value, true)] = $names;
            }
        }
        if ($ambiguous) {
            throw new LogicException(
                'All values must be unique. The following are ambiguous: '
                . implode(
                    ', ',
                    array_map(
                        function ($keys, $value) {
                            return implode('/', $keys) . " = $value";
                        },
                        array_keys($ambiguous),
                        array_values($ambiguous)
                    )
                )
            );
        }

        //Reordering to keep parent constants first - to keep indices
        while (
            ($reflection = $reflection->getParentClass())
            && $reflection->name !== __CLASS__
        ) {
            $constants = $reflection->getConstants() + $constants;
        }

        self::$constants[$class] = $constants;
        return $constants;
    }
}
