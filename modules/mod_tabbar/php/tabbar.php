<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;
use UltimateBackend\lib\Modules;


class Tabbar implements Module
{
    private $properties = array();
    private $Template = null;

    public function __construct($props, Template $Tmpl = null)
    {
        $this->Template = $Tmpl ? $Tmpl : Template::load("modules/mod_tabbar/template/tabbar.html");
        $this->properties = $props;

        Base::setHeaderFiles(array(
            'css' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxTabbar/codebase/skins/dhtmlxtabbar_dhx_skyblue.css"
            ),
            'js' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxCommon/codebase/dhtmlxcommon.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxCommon/codebase/dhtmlxcontainer.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxTabbar/codebase/dhtmlxtabbar.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxTabbar/codebase/dhtmlxtabbar_start.js"
            )
        ));
    }

	public function render()
	{
        $ModDemo = Modules::factory("Demo");
        $marker['###MOD_DEMO###'] = $ModDemo->render();
        return $this->Template->parse($marker);
	}

}
