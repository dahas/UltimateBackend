<?php

use UltimateBackend\lib\Module;
use UltimateBackend\lib\Tools;
use UltimateBackend\lib\Template;


class Demo extends Module
{
    public function __construct($_get, Template $Tmpl = null)
    {
        Module::__construct($_get, $Tmpl);

        if(!$this->Template)
            $this->Template = Template::load("modules/mod_demo/template/demo.html");

        Tools::setHeaderFiles(array(
            'css' => array("modules/mod_demo/template/demo.css")
        ));
    }

	public function render()
	{
        $demos = $this->DB->select([
            "columns" => "text",
            "from" => "ub_demo",
            "where" => "id=3"
        ]);
        $demo = $demos->getRow();
        $ModTest = Module::create("Test", $this->get);
        $marker["###CONTENT###"] = $ModTest->render();
        $marker["###DB_TEXT###"] = $demo[0];
        return $this->Template->parse($marker);
	}
}
