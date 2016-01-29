<?php
namespace Olc\Form\Plugins;

use Olc\Data\Zipper;
use Olc\Form\Environment;
use Olc\Form\Types\DataSource;

class FileEnvironmentEnricher extends Plugin
{
    public function renderBefore_10(Zipper $z)
    {
        $this->setEnvironment($z);
    }

    public function renderAfter_10(Zipper $z)
    {
        $this->unsetEnvironment($z);
    }

    public function submitBefore_100(Zipper $z)
    {
        $this->setEnvironment($z);
    }

    public function submitAfter_100(Zipper $z)
    {
        $this->unsetEnvironment($z);
    }

    public function setEnvironment(Zipper $z)
    {
        $current = $z->getContent();
        $data = $this->getData();
        $env = new Environment($current->get('initialFileData'), $data);
        $current->set('fileEnvironment', $env);
    }

    public function unsetEnvironment(Zipper $z)
    {
        $current = $z->getContent();
        $current->set('fileEnvironment', null);
    }

    public function getData()
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
