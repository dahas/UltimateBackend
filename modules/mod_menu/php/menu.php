<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;


class Menu extends Module
{
    public $menuID = "main_menu";

    public function __construct($props, Template $Tmpl = null)
    {
        Module::__construct($props, $Tmpl);

        if(!$this->Template)
            $this->Template = Template::load("modules/mod_menu/template/menu.html");

        Base::setHeaderFiles(array(
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
