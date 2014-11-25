<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;
use UltimateBackend\lib\Modules;


class Layout implements Module
{
    private $properties = array();
    private $Template = null;

    public $additionalFiles = array();

    public function __construct($props)
    {
        $this->Template = Template::load("modules/mod_layout/template/layout.html");

        $this->properties = $props;

        Base::setHeaderFiles(array(
            'css' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxLayout/codebase/skins/dhtmlxlayout_dhx_terrace.css",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxTree/codebase/skins/dhtmlxTree_dhx_terrace.css"
            ),
            'js' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxCommon/codebase/dhtmlxcommon.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxCommon/codebase/dhtmlxcontainer.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxLayout/codebase/dhtmlxlayout.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxTree/codebase/dhtmlxtree.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxTree/codebase/ext/dhtmlxtree_json.js"
            )
        ));
        Base::setBodyOnload('doOnLoad();');
    }

    public function render()
    {
        $ModMenu = Modules::factory("Menu");
        $marker['###MAIN_MENU###'] = $ModMenu->render();



        $marker['###TREE_DATA_URL###'] = "modules/mod_layout/template/tree_data.json";
        $marker['###C_ATTACH_URL###'] = "index.php?mod=nested_layout";
        return $this->Template->parse($marker);
    }

}
