<?php
namespace Olc\Data;

class Tagged extends Record
{
    protected $_fields;

    public function __construct()
    {
        if (func_num_args() != count($this->_fields)) {
            throw new \InvalidArgumentException('Invalid parameter count');
        }
        parent::__construct(array_combine($this->_fields, func_get_args()));
    }
}
