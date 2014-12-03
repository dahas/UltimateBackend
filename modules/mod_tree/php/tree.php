<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;


class Tree implements Module
{
    private $properties = array();
    private $Template = null;

    public $data_link = "?mod=tree&task=loadData";

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
        $marker['###DATA_URL###'] = $this->data_link;
        return $this->Template->parse($marker);
	}

    /**
     * Task to load the tree items.
     */
    public function loadData()
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
