<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;


class Grid implements Module
{
    private $properties = array();
    private $Template = null;

    public $additional_files = array();

    public function __construct($props)
    {
        $this->Template = Template::load("modules/mod_grid/template/grid.html");
        $this->properties = $props;

        Base::setHeaderFiles(array(
            'css' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_terrace.css"
            ),
            'js' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxCommon/codebase/dhtmlxcommon.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxGrid/codebase/dhtmlxgrid.js",
            )
        ));
    }

	public function render()
	{
        $marker['###DATA_URL###'] = "modules/mod_grid/template/data.json";
        return $this->Template->parse($marker);
	}

}
