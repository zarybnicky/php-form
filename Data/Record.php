<?php
namespace Olc\Data;

class Record
{
    public function __construct($fields = array())
    {
        $fields = is_array($fields)
                ? $fields
                : array_combine(func_get_args(), array_fill(0, func_num_args(), null));
        foreach ($fields as $k => $v) {
            $this->$k = $v;
        }
    }

    public function __call($name, $args)
    {
        if (!property_exists($this, $name) || $name == '_fields') {
            throw new \InvalidArgumentException('Invalid field name');
        }
        if ($args) {
            return $this->$name = $args[0];
        } else {
            return $this->$name;
        }
    }

    public function fields()
    {
        $props = get_object_vars($this);
        unset($props['_fields']);
        return $props;
    }

    public function unpack($fields = array())
    {
        if (!$fields) {
            return array_values($this->fields());
        }
        $fields = is_array($fields) ? $fields : func_get_args();
        $data = $this->fields();
        $result = array();
        foreach ($fields as $k) {
            $result[] = isset($data[$k]) ? $data[$k] : null;
        }
        return $result;
    }

    public function match($pattern)
    {
        $pattern = is_array($pattern) ? $pattern : func_get_args();
        $zipped = array_map(null, $pattern, array_slice($this->fields(), 0, count($pattern)));
        foreach ($zipped as $xs) {
            list($p, $x) = $xs;
            //Allow nulls as wildcards
            if ($p !== null && $p !== $x) {
                return false;
            }
        }
        return true;
    }

    public function __toString()
    {
        return '[' . implode(', ', $this->fields()) . ']';
    }
}
