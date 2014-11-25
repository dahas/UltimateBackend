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

    public function __construct($props)
    {
        $this->Template = Template::load($this->htmlFile);
        $this->properties = $props;
    }

    public function render()
    {
        $Module = Modules::factory("Layout");
        $this->additionalFiles = $Module->additionalFiles;

        $marker['###CONTENT###'] = 'Here is the content!';

        return $this->Template->parse($marker);
    }

}
