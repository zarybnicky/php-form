<?php
include 'autoload.php';

//widget - name, value, attributes, errors, label
//layout - widgets, labels, shape

//form -> view, result, isValid

//environment - strings $_GET, $_POST, others $_SERVER, $_ENV, $_SESSION
//file environment = FileInfos

use Olc\Widget\Tag;

$li = new Tag(
    'li', array('class' => 'Item'),
    new Tag('h2', array('class' => 'Item-name'), 'Name Surname'),
    new Tag(
        'a', array('class' => 'Item-mail', 'href' => 'mailto:name.surname@mail.com'),
        'mailto:name.surname@mail.com'
    ),
    new Tag('br'),
    new Tag('div', array('class' => 'Item-desc'), 'Description')
);
//echo $li->render();

$it = new Olc\Widget\InputText(array('name' => 'name', 'value' => 'value',
'disabled' => true));
echo $it->render();