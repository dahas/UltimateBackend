<?php

use UltimateBackend\lib\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Tools;


class Test extends Module
{
    public function __construct($_get, Template $Tmpl = null)
    {
        Module::__construct($_get, $Tmpl);

        if(!$this->Template)
            $this->Template = Template::load("modules/mod_test/template/test.html");

        Tools::setHeaderFiles(array(
            'css' => array("modules/mod_test/template/test.css")
        ));
    }

	public function render()
	{
        /*$subProperties = $this->Template->getSubpart('###PROPERTIES###');
        $subpart['###PROPERTIES###'] = '';
        foreach($this->get as $key => $value) {
            $markerProp["###KEY###"] = $key;
            $markerProp["###VALUE###"] = $value;
            $subpart['###PROPERTIES###'] .= $subProperties->parse($markerProp);
        }*/

        $tests = $this->DB->select([
            "columns" => "name",
            "from" => "ub_test",
            "where" => "id=2"
        ]);
        $test = $tests->getRow();

        $marker["###CONTENT###"] = $test[0];
        return $this->Template->parse($marker);
	}

}
