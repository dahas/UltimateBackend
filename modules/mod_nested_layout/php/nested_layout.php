<?php

use UltimateBackend\lib\Module;
use UltimateBackend\lib\Template;


class Nested_Layout extends Module
{
    public function __construct($_get, Template $Tmpl = null)
    {
        Module::__construct($_get, $Tmpl);

        if(!$this->Template)
            $this->Template = Template::load("modules/mod_nested_layout/template/nested_layout.html");
    }

    public function render()
    {
        Module::create("Layout");

        $marker['###CONTENT###'] = 'Here is the content!';
        $marker['###MOD_GRID###'] = "?mod=grid";

        return $this->Template->parse($marker);
    }

}
