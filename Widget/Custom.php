<?php
namespace Olc\Widget;

class Custom extends Widget
{
    protected $renderFn;

    public function __construct($renderFn)
    {
        $this->renderFn = $renderFn;
    }

    public function render()
    {
        $fn = $this->renderFn;
        $result = $fn($this->attributes, $this->children);
        if (!is_string($result)) {
            trigger_error(
                'Custom render function returned a '
                . gettype($result) . ', not a string!',
                E_USER_WARNING
            );
        }
        return $result;
    }
}
