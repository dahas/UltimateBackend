<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;


class Demo implements Module
{
    private $Template = null;
	private $properties = array();

    public function __construct($props)
    {
        $this->Template = Template::load("modules/mod_demo/template/demo.html");
        $this->properties = $props;
    }

	public function render()
	{
        $marker["###CONTENT###"] = "Demo Module";
        return $this->Template->parse($marker);
	}

}
