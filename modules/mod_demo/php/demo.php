<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Modules;
use UltimateBackend\lib\Base;
use UltimateBackend\lib\Template;


class Demo extends Module
{
    public function __construct($props, Template $Tmpl = null)
    {
        Module::__construct($props, $Tmpl);

        if(!$this->Template)
            $this->Template = Template::load("modules/mod_demo/template/demo.html");

        Base::setHeaderFiles(array(
            'css' => array("modules/mod_demo/template/demo.css")
        ));
    }

	public function render()
	{
        $ModTest = Modules::factory("Test", $this->properties, Template::load("modules/mod_demo/template/test.html"));
        $marker["###CONTENT###"] = $ModTest->render();
        return $this->Template->parse($marker);
	}
}
