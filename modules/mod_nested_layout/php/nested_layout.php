<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;
use UltimateBackend\lib\Modules;


class Nested_Layout extends Module
{
    public function __construct($props, Template $Tmpl = null)
    {
        Module::__construct($props, $Tmpl);

        if(!$this->Template)
            $this->Template = Template::load("modules/mod_nested_layout/template/nested_layout.html");
    }

    public function render()
    {
        Modules::factory("Layout");

        $marker['###CONTENT###'] = 'Here is the content!';
        $marker['###MOD_GRID###'] = "?mod=grid";

        return $this->Template->parse($marker);
    }

}
