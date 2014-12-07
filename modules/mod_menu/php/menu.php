<?php

use UltimateBackend\lib\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Tools;


class Menu extends Module
{
    public $menuID = "main_menu";

    public function __construct($_get, Template $Tmpl = null)
    {
        Module::__construct($_get, $Tmpl);

        if(!$this->Template)
            $this->Template = Template::load("modules/mod_menu/template/menu.html");

        Tools::setHeaderFiles(array(
            'css' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_skyblue.css"
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
