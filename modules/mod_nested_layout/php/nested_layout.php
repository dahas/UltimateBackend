<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;
use UltimateBackend\lib\Modules;


class Nested_Layout implements Module
{
    private $properties = array();
    private $Template = null;

    public $htmlFile = "modules/mod_nested_layout/template/nested_layout.html";
    public $additionalFiles = array();

    public function __construct($props, Template $Tmpl = null)
    {
        $this->Template = $Tmpl ? $Tmpl : Template::load($this->htmlFile);
        $this->properties = $props;
    }

    public function render()
    {
        Modules::factory("Layout");

        $marker['###CONTENT###'] = 'Here is the content!';

        return $this->Template->parse($marker);
    }

}
