<?php
namespace Olc\Validation;

abstract class Validator implements ValidatorInterface
{
    protected $success;
    protected $errorMsg;

    public function __construct($msg = null)
    {
        if ($msg !== null) {
            $this->errorMsg = $msg;
        }
    }

    public function validate($x)
    {
        return $this->success = $this->process($x);
    }

    abstract protected function process($x);

    public function getMessages()
    {
        return $this->success
            ? array()
            : array($this->errorMsg);
    }
}
