<?php

use UltimateBackend\lib\Module;
use UltimateBackend\lib\Template;


class Nested_Layout extends Module
{
    public function __construct($_get, Template $Tmpl = null)
    {
        parent::__construct($_get, $Tmpl);

        if(!$this->Template)
            $this->Template = Template::load("modules/mod_nested_layout/template/nested_layout.html");
    }

    public function render($html = "")
    {
        Module::create("Layout");

        $marker['###CONTENT###'] = 'Here is the content!';
        $marker['###MOD_GRID###'] = "?mod=grid";

        return $this->Template->parse($marker);
    }

}
