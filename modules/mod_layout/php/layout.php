<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;
use UltimateBackend\lib\Modules;


class Layout implements Module
{
    private $properties = array();
    private $Template = null;

    public function __construct($props, Template $Tmpl = null)
    {
        $this->properties = $props;
        {
            $this->Template = $Tmpl ? $Tmpl : Template::load("modules/mod_layout/template/layout.html");

            Base::setHeaderFiles(array(
                'css' => array(
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxLayout/codebase/skins/dhtmlxlayout_dhx_skyblue.css",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxTabbar/codebase/skins/dhtmlxtabbar_dhx_skyblue.css",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxTree/codebase/skins/dhtmlxtree_dhx_skyblue.css"
                ),
                'js' => array(
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxCommon/codebase/dhtmlxcontainer.js",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxLayout/codebase/dhtmlxlayout.js",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxTabbar/codebase/dhtmlxtabbar.js",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxTree/codebase/dhtmlxtree.js",
                    "resources/dhtmlxSuite_v403_std/sources/dhtmlxTree/codebase/ext/dhtmlxtree_json.js"
                )
            ));
        }
    }

    /**
     * Task to parse and return a valid html file.
     * @return string HTML
     */
    public function render()
    {
        $ModMenu = Modules::factory("Menu");
        $marker['###MAIN_MENU###'] = $ModMenu->render();
        $marker['###MAIN_MENU_ID###'] = $ModMenu->menuID;

        $marker['###TREE_DATA_URL###'] = "?mod=layout&task=loadTree";
        $marker['###TAB_DATA_URL###'] = "?mod=layout&task=loadTabs";

        $marker['###GRID_URL###'] = "?mod=grid";

        return $this->Template->parse($marker);
    }

    /**
     * Task to load the tabbar items.
     */
    public function loadTabs()
    {
        $data = '{
            tabs: [
                {
                    id: "a1",      // tab id
                    text: "Customer Details",    // tab text
                    width: null,      // numeric for tab width or null for auto, optional
                    index: null,      // numeric for tab index or null for last position, optional
                    active: true,      // boolean, make tab active after adding, optional
                    enabled: true,     // boolean, false to disable tab on init
                    close: false       // boolean, render close button on tab, optional
                },
                {
                    id: "a2",      // tab id
                    text: "Documents",    // tab text
                    width: null,      // numeric for tab width or null for auto, optional
                    index: null,      // numeric for tab index or null for last position, optional
                    active: false,      // boolean, make tab active after adding, optional
                    enabled: true,     // boolean, false to disable tab on init
                    close: false,       // boolean, render close button on tab, optional
                    href: "?mod=demo"
                },
                {
                    id: "a3",      // tab id
                    text: "Statistics",    // tab text
                    width: null,      // numeric for tab width or null for auto, optional
                    index: null,      // numeric for tab index or null for last position, optional
                    active: false,      // boolean, make tab active after adding, optional
                    enabled: true,     // boolean, false to disable tab on init
                    close: false,       // boolean, render close button on tab, optional
                    href: "?mod=grid"
                },
                {
                    id: "a4",      // tab id
                    text: "Report",    // tab text
                    width: null,      // numeric for tab width or null for auto, optional
                    index: null,      // numeric for tab index or null for last position, optional
                    active: false,      // boolean, make tab active after adding, optional
                    enabled: true,     // boolean, false to disable tab on init
                    close: false,       // boolean, render close button on tab, optional
                    href: "?mod=nested_layout"
                }
            ]
        }';

        header('Content-Type: application/json');
        echo($data);
    }

    /**
     * Task to load the tree items.
     */
    public function loadTree()
    {
        $data = '{id: 0, item: [
            {id: "100332", text: "Smith Inc.", select:"1"},
            {id: "101544", text: "Clean Steel Foundation", item: [
                {id: "101544-224", text: "CSF Miami"},
                {id: "101544-746", text: "CSF New York"},
                {id: "101544-355", text: "CSF Phoenix Arizona"}
            ]},
            {id: "104634", text: "Rutherford & Wagner"},
            {id: "103997", text: "Bullhead Inc."},
            {id: "103135", text: "John H. Muller"}
        ]}';

        header('Content-Type: application/json');
        echo($data);
    }

}
