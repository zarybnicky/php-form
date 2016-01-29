<?php
include 'autoload.php';

class_exists('\\Olc\\Advice\\Bounce');
class_exists('\\Olc\\Advice\\Manager');
class_exists('\\Olc\\Advice\\Expression\\Apply');
class_exists('\\Olc\\Advice\\Expression\\Base');
class_exists('\\Olc\\Advice\\Expression\\Call');
class_exists('\\Olc\\Advice\\Expression\\Force');
class_exists('\\Olc\\Advice\\Expression\\Prog1');
class_exists('\\Olc\\Advice\\Expression\\Prog2');
class_exists('\\Olc\\Advice\\Expression\\Rest');
class_exists('\\Olc\\Data\\Enum');
class_exists('\\Olc\\Data\\Monoid');
class_exists('\\Olc\\Data\\Zipper');
class_exists('\\Olc\\Form\\Environment');
class_exists('\\Olc\\Form\\Fields\\Text');
class_exists('\\Olc\\Form\\Fields\\Password');
class_exists('\\Olc\\Form\\Plugins\\Plugin');
class_exists('\\Olc\\Form\\Plugins\\EnctypeReducer');
class_exists('\\Olc\\Form\\Plugins\\EnvironmentEnricher');
class_exists('\\Olc\\Form\\Plugins\\FileEnvironmentEnricher');
class_exists('\\Olc\\Form\\Plugins\\Generator');
class_exists('\\Olc\\Form\\Plugins\\Group');
class_exists('\\Olc\\Form\\Plugins\\TopLevel');
class_exists('\\Olc\\Form\\Plugins\\Traverse');
class_exists('\\Olc\\Form\\Plugins\\Validator');
class_exists('\\Olc\\Form\\Plugins\\Wrapper');
class_exists('\\Olc\\Form\\Types\\DataSource');
class_exists('\\Olc\\Form\\Types\\DataStatus');
class_exists('\\Olc\\Form\\Types\\Enctype');
class_exists('\\Olc\\Validation\\ValidationInterface');
class_exists('\\Olc\\Validation\\Base');
class_exists('\\Olc\\Validation\\NotEmpty');
class_exists('\\Olc\\Widget\\Widget');
class_exists('\\Olc\\Widget\\SimpleDivs');
class_exists('\\Olc\\Widget\\Tag');

use Olc\Form\Form;
use Olc\Form\Fields;
use Olc\Form\Plugins\EnctypeReducer;
use Olc\Form\Plugins\ErrorReducer;
use Olc\Form\Plugins\Generator;
use Olc\Form\Plugins\Validator;
use Olc\Form\Plugins\Traverse;
use Olc\Form\Plugins\ValueReducer;
use Olc\Form\Plugins\Wrapper;
use Olc\Form\Types\DataSource;
use Olc\Validation;
use Olc\Widget;
use Olc\Widget\Tag;

class TestForm extends Form
{
    protected function initialize()
    {
        $username = new Fields\Text('User name');
        $username->with(new Validator(new Validation\NotEmpty('Username must not be empty!')));
        $password = new Fields\Password('Password');
        $password->with(new Validator(new Validation\NotEmpty()));

        $this->add($username);
        $this->add($password);

        $this->set('method', DataSource::POST());
        $this->with(new Traverse());
        $this->with(new ValueReducer());
    }
}

//xdebug_start_trace('form');

function toList($xs)
{
    $res = array();
    foreach ($xs as $x) {
        $res[] = new Tag('li', array(), $x);
    }
    return $res ? new Tag('ul', array(), $res) : '(empty)';
}
function convert($size)
{
    $unit = array('B','kiB','MiB','GiB','TiB','PiB');
    $i = floor(log($size, 1024));
    return round($size / pow(1024, $i), 2) . ' ' . $unit[$i];
}

echo convert(memory_get_usage()), "\n";

$_SERVER['REQUEST_METHOD'] = 'POST';
$x = new TestForm('table');
$x->with(new Generator(new Widget\SimpleTable()));
$x->run();

echo convert(memory_get_usage()), "\n";

$y = new TestForm('divs');
$y->with(new Generator(new Widget\SimpleDivs()));
$y->run();

echo convert(memory_get_usage()), "\n";

$f = new TestForm('form');
$f->with(new Generator(new Widget\SimpleDivs(array("submit" => false))));

$t = new Fields\Text('42?');
$t->with(new Validator(new Validation\Custom(
    function ($x) {
        return $x === '42';
    },
    '"42?" must be 42.'
)));

$z = new Fields\Void('nested');
$z->add($f);
$z->add($t);

foreach (range(0, 2000) as $i) {
    $field = new Fields\Text();
    $field->with(new Validator(new Validation\Custom(
        function ($x) {
            return $x === '42';
        },
        '"42?" must be 42.'
    )));
    $z->add($field);
}

$z->set('method', DataSource::POST());
$z->with(new Generator(new Widget\SimpleTable()));
$z->run();

echo convert(memory_get_usage()), "\n";

echo convert(memory_get_peak_usage()), "\n";
exit;
?>
<html>
    <body>
        <div style="display:flex">
            <div style="flex:auto">
                <h1>Table</h1>
                <h2>Errors</h2>
                <?= var_dump($x->get('errors')) ?>
                <h2>Result</h2>
                <?php var_dump($x->get('result')); ?>
                <h2>View</h2>
                <?= $x->get('view') ?>
            </div>
            <div style="flex:auto">
                <h1>Divs</h1>
                <h2>Errors</h2>
                <?= var_dump($y->get('errors')) ?>
                <h2>Result</h2>
                <?php var_dump($y->get('result')); ?>
                <h2>View</h2>
                <?= $y->get('view') ?>
            </div>
            <div style="flex:auto">
                <h1>Nested</h1>
                <h2>Errors</h2>
                <?= var_dump($z->get('errors')) ?>
                <h2>Result</h2>
                <?php var_dump($z->get('result')); ?>
                <h2>View</h2>
                <?= $z->get('view') ?>
            </div>
        </div>
    </body>
</html>
