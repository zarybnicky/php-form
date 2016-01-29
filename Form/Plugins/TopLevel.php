<?php
namespace Olc\Form\Plugins;

class TopLevel extends Group
{
    public function __construct()
    {
        $this->addPlugin(new EnvironmentEnricher());
        $this->addPlugin(new FileEnvironmentEnricher());
        $this->addPlugin(new ErrorReducer());
        $this->addPlugin(new EnctypeReducer());
        $this->addPlugin(new ValueReducer());
        $this->addPlugin(new Wrapper());
        $this->addPlugin(new Traverse());
    }
}