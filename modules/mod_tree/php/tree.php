<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;


class Tree implements Module
{
    private $properties = array();
    private $Template = null;

    public $additional_files = array();

    public function __construct($props, Template $Tmpl = null)
    {
        $this->Template = $Tmpl ? $Tmpl : Template::load("modules/mod_tree/template/tree.html");
        $this->properties = $props;

        Base::setHeaderFiles(array(
            'css' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxTree/codebase/skins/dhtmlxTree_dhx_terrace.css"
            ),
            'js' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxCommon/codebase/dhtmlxcommon.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxTree/codebase/dhtmlxtree.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxTree/codebase/ext/dhtmlxtree_json.js"
            )
        ));
    }

	public function render()
	{
        $marker['###DATA_URL###'] = "modules/mod_tree/data/tree.json";
        return $this->Template->parse($marker);
	}

}
