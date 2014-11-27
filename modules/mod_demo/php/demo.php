<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;


class Demo implements Module
{
    private $properties = array();
    private $Template = null;

    public $additional_files = array();

    public function __construct($props, Template $Tmpl = null)
    {
        $this->Template = $Tmpl ? $Tmpl : Template::load("modules/mod_demo/template/demo.html");
        $this->properties = $props;

        $this->additionalFiles = array(
            'css' => array("modules/mod_demo/template/demo.css"),
        );
    }

	public function render()
	{
        $marker["###CONTENT###"] = "Demo Module";
        return $this->Template->parse($marker);
	}

}
