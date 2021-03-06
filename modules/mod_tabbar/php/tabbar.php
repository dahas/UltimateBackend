<?php

use UltimateBackend\lib\Template;
use UltimateBackend\lib\Tools;
use UltimateBackend\lib\Module;


class Tabbar extends Module
{
    public function __construct(Template $Tmpl = null)
    {
        parent::__construct($Tmpl);

        if(!$this->Template)
            $this->Template = Template::load("modules/mod_tabbar/template/tabbar.html");

        Tools::setHeaderFiles([
            'css' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxTabbar/codebase/skins/dhtmlxtabbar_dhx_skyblue.css"
            ),
            'js' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxCommon/codebase/dhtmlxcommon.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxCommon/codebase/dhtmlxcontainer.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxTabbar/codebase/dhtmlxtabbar.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxTabbar/codebase/dhtmlxtabbar_start.js"
            )
        ]);
    }

	public function render($html = "")
	{
        $ModDemo = Module::create("Demo");
        $marker['###MOD_DEMO###'] = $ModDemo->render();
        return $this->Template->parse($marker);
	}

}
