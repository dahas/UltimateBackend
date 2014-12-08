<?php

use UltimateBackend\lib\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Tools;


class Menu extends Module
{
    public $menuID = "main_menu";

    public function __construct($_get, Template $Tmpl = null)
    {
        parent::__construct($_get, $Tmpl);

        if(!$this->Template)
            $this->Template = Template::load("modules/mod_menu/template/menu.html");

        Tools::setHeaderFiles([
            'css' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_skyblue.css"
            ),
            'js' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxMenu/codebase/dhtmlxmenu.js",
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxMenu/codebase/ext/dhtmlxmenu_ext.js"
            )
        ]);
    }

    public function render($html = "")
    {
        $marker['###MENU_ID###'] = $this->menuID;
        return $this->Template->parse($marker);
    }

}
