<?php
namespace Olc\Form\Types;

use \SplFileInfo;

class FileInfo extends SplFileInfo
{
    protected $originalName;

    public function __construct(FileStatus $status, $tmpName, $originalName)
    {
        parent::__construct($tmpName);
        $this->status = $status;
        $this->originalName = $originalName;
    }

    public function getStatus()
    {
        return $this->getStatus;
    }

    public function getOriginalName()
    {
        return $this->originalName;
    }
}