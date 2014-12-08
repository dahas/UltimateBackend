<?php

use UltimateBackend\lib\Module;
use UltimateBackend\lib\Tools;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\DB;
use UltimateBackend\lib\Recordset;


class Demo extends Module
{
    private $DB = null;

    public function __construct(Template $Tmpl = null)
    {
        parent::__construct($Tmpl);

        if (!$this->Template)
            $this->Template = Template::load("modules/mod_demo/template/demo.html");

        Tools::setHeaderFiles([
            'css' => array("modules/mod_demo/template/demo.css")
        ]);

        $this->DB = DB::getInstance(
            $this->config['database']['DB_Name'],
            $this->config['database']['Host'],
            $this->config['database']['Username'],
            $this->config['database']['Password'],
            $this->config['database']['Charset']
        );
    }

    public function render($html = "")
    {
        $demos = $this->DB->select([
            "columns" => "text",
            "from" => "ub_demo",
            "where" => "id=3"
        ]);

        $demo = $demos->getRow(Recordset::FETCH_OBJECT);

        $ModTest = Module::create("Test", Template::load("modules/mod_demo/template/test.html"));
        $marker["###CONTENT###"] = $ModTest->render('Filtered $_GET Parameters');

        $marker["###DB_TEXT###"] = $demo->text;

        return $this->Template->parse($marker);
    }
}
