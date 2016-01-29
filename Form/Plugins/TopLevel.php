<?php
namespace Olc\Form\Plugins;

class TopLevel extends Group
{
    public function getPlugins()
    {
        return array(
            new EnvironmentEnricher(),
            new FileEnvironmentEnricher(),
            new ErrorReducer(),
            new EnctypeReducer(),
            new ValueReducer(),
            new Wrapper(),
            new Traverse()
        );
    }
}