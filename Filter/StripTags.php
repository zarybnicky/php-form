<?php
namespace \Olc\Filter;

class StripTags implements FilterInterface
{
    protected $whitelist;

    public function __construct($whitelist = '')
    {
        $this->whitelist = $whitelist;
    }

    public function filter($x)
    {
        return strip_tags($x, $this->whitelist);
    }
}
