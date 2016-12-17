<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Form\Environment;
use Olc\Validation\ValidatorInterface;

class Validator extends Plugin
{
    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct();
        $this->name = 'Validator (' . get_class($validator) . ')';

        $this->addAdvice(
            array('submit', 0, 'before'),
            self::getValidator($validator)
        );
    }

    public static function getValidator(ValidatorInterface $validator)
    {
        $class = get_class();
        return function (Zipper $x) use ($class, $validator) {
            $class::validate($x, $validator);
        };
    }

    public static function validate(Zipper $x, ValidatorInterface $validator)
    {
        $current = $x->getContent();
        list(, $data) = $x->getRoot()->get('environment')->get($x->getPath());

        $validator->validate($data);

        $current->set(
            'errors',
            array_merge(
                $current->get('errors') ?: array(),
                $validator->getMessages()
            )
        );
    }
}
