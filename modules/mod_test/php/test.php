<?php

use UltimateBackend\lib\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\DB;
use UltimateBackend\lib\Tools;
use UltimateBackend\lib\Recordset;


class Test extends Module
{
    public function __construct(Template $Tmpl = null)
    {
        parent::__construct($Tmpl);

        if(!$this->Template)
            $this->Template = Template::load("modules/mod_test/template/test.html");

        Tools::setHeaderFiles([
            'css' => array("modules/mod_test/template/test.css")
        ]);

        $this->DB = DB::getInstance();
    }

	public function render($html = "")
	{
        $marker["###HEADER###"] = $html;

        $subProperties = $this->Template->getSubpart('###PROPERTIES###');
        $subpart['###PROPERTIES###'] = '';
        foreach($this->_get as $key => $value) {
            $markerProp["###KEY###"] = $key;
            $markerProp["###VALUE###"] = $value;
            $subpart['###PROPERTIES###'] .= $subProperties->parse($markerProp);
        }

        $tests = $this->DB->select([
            "columns" => "name",
            "from" => "ub_test",
            "where" => "id=2"
        ]);
        $test = $tests->getRow(Recordset::FETCH_OBJECT);

        $marker["###CONTENT###"] = $test->name;
        return $this->Template->parse($marker,$subpart);
	}

}
