<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;


class Test extends Module
{
    public function __construct($props, Template $Tmpl = null)
    {
        Module::__construct($props, $Tmpl);

        if(!$this->Template)
            $this->Template = Template::load("modules/mod_test/template/test.html");

        Base::setHeaderFiles(array(
            'css' => array("modules/mod_test/template/test.css")
        ));
    }

	public function render()
	{
        $subProperties = $this->Template->getSubpart('###PROPERTIES###');
        $subpart['###PROPERTIES###'] = '';
        foreach($this->properties as $key => $value) {
            $markerProp["###KEY###"] = $key;
            $markerProp["###VALUE###"] = $value;
            $subpart['###PROPERTIES###'] .= $subProperties->parse($markerProp);
        }
        $marker["###CONTENT###"] = "TEST Module";
        return $this->Template->parse($marker, $subpart);
	}

}
