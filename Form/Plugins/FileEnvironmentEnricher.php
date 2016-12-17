<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Form\Environment;
use Olc\Form\Types\DataSource;

class FileEnvironmentEnricher extends Plugin
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'FileEnvironmentEnricher';

        $set = get_class() . '::setEnvironment';
        $unset = get_class() . '::unsetEnvironment';

        $this->addAdvice(array('render', -100, 'before'), $set);
        $this->addAdvice(array('submit', -100, 'before'), $set);
        $this->addAdvice(array('render', -100, 'after'), $unset);
        $this->addAdvice(array('submit', -100, 'after'), $unset);
    }

    public static function setEnvironment(Zipper $z)
    {
        $current = $z->getContent();
        $data = self::getData();
        $env = new Environment($current->get('initialFileData'), $data);
        $current->set('fileEnvironment', $env);
    }

    public static function unsetEnvironment(Zipper $z)
    {
        $current = $z->getContent();
        $current->set('fileEnvironment', null);
    }

    public static function getData()
    {
        $result = array();
        foreach ($_FILES as $name => $input) {
            if (is_array($input['error'])) {
                $result[$name] = array();
                foreach ($input['error'] as $key => $error) {
                    $result[$name][] = new FileInfo(
                        FileStatus::fromUpload($error),
                        $input['tmp_name'][$key],
                        $input['name'][$key]
                    );
                }
            } else {
                $result[$name] = new FileInfo(
                    FileStatus::fromUpload($input['error']),
                    $input['tmp_name'],
                    $input['name']
                );
            }
        }
        return $result;
    }
}
