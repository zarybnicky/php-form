<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Form\Environment;
use Olc\Validation\ValidatorInterface;

class Validator extends Plugin
{
    protected $validator;

    public function __construct(ValidatorInterface $x)
    {
        $this->validator = $x;
        $this->addAdvice(array('submit', 0, 'before'), array($this, 'validate'));
    }

    public function validate(Zipper $x)
    {
        $current = $x->getContent();
        list(, $data) = $x->getRoot()->get('environment')->get($x->getPath());

        $this->validator->validate($data);

        $current->set(
            'errors',
            array_merge(
                $current->get('errors') ?: array(),
                $this->validator->getMessages()
            )
        );
    }

    public function getName()
    {
        return 'validator-' . get_class($this->validator);
    }
}
