<?php

include 'autoload.php';

class_exists('\\Olc\\Data\\Enum');
class_exists('\\Olc\\Data\\Monoid');
class_exists('\\Olc\\Form\\Type\\Enctype');

xdebug_start_trace('monoid');

use Olc\Form\Type\Enctype;
echo Enctype::URL_ENCODED()->mappend_(Enctype::MULTIPART())->getValue();