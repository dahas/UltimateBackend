<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;


class Menu implements Module
{
    private $properties = array();
    private $Template = null;

    public $additional_files = array();
    public $menuID = "main_menu";

    public function __construct($props)
    {
        $this->Template = Template::load("modules/mod_menu/template/menu.html");
        $this->properties = $props;

        Base::setHeaderFiles(array(
            'css' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_terrace.css"
            ),
            'js' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxMenu/codebase/dhtmlxmenu.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxMenu/codebase/ext/dhtmlxmenu_ext.js"
            )
        ));
    }

	public function render()
	{
        $marker['###MENU_ID###'] = $this->menuID;
        return $this->Template->parse($marker);
	}

}
