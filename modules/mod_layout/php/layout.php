<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;


class Layout implements Module
{
    private $properties = array();
    private $Template = null;

    public $additionalFiles = array();
    public $html = '';

    public function __construct($props)
    {
        $this->Template = Template::load("modules/mod_layout/template/layout.html");

        $this->properties = $props;

        if (empty($this->additionalFiles)) {
            $this->additionalFiles = array(
                'css' => array(
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxLayout/codebase/skins/dhtmlxlayout_dhx_terrace.css",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxTree/codebase/skins/dhtmlxTree_dhx_terrace.css",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_terrace.css"
                ),
                'js' => array(
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxCommon/codebase/dhtmlxcommon.js",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxCommon/codebase//dhtmlxcontainer.js",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxCommon/codebase//dhtmlxcore.js",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxLayout/codebase/dhtmlxlayout.js",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxTree/codebase/dhtmlxtree.js",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxTree/codebase/ext/dhtmlxtree_json.js",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxGrid/codebase/dhtmlxgrid.js",
                )
            );
        }

        Base::setBodyOnload('doOnLoad();');
    }

    public function render()
    {
        $aTreeData = '{id: 0, item: [
                            {id: 1, text: "Load a grid into Content pane"},
                            {id: 2, text: "Example of a folder item", item: [
                                {id: "2-1", text: "First Child"},
                                {id: "2-2", text: "Second Child"},
                                {id: "2-3", text: "Third Child", item: [
                                    {id: "2-3-1", text: "First SubChild"},
                                    {id: "2-3-2", text: "Second SubChild"},
                                    {id: "2-3-3", text: "Third SubChild"}
                                ]}
                            ]},
                            {id: 3, text: "Load accorion into Content pane"},
                            {id: 4, text: "Fourth Node"}
                        ]}';

        $marker['###A_TREE_DATA###'] = $aTreeData;
        $marker['###C_ATTACH_URL###'] = "index.php?mod=nested_layout";
        return $this->Template->parse($marker);
    }

}
