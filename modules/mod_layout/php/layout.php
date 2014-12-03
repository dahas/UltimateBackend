<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;
use UltimateBackend\lib\DB;
use UltimateBackend\lib\Recordset;
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

        $marker['###LEFT_PANE_TITLE###'] = "Hersteller";
        $marker['###RIGHT_PANE_TITLE###'] = "Produkte";

        $marker['###TREE_DATA_URL###'] = "?mod=layout&task=loadTree";
        $marker['###TAB_DATA_URL###'] = "?mod=layout&task=loadTabs";

        $marker['###PRODUCTS_URL###'] = "?mod=products";

        return $this->Template->parse($marker);
    }

    /**
     * Task to load the tree items.
     */
    public function loadTree()
    {
        $db = DB::connect();
        $companies = $db->select("
            SELECT h.id, h.name, COUNT(b.id) AS hasBranch
            FROM ub_companies_hq h
            LEFT JOIN ub_companies_branch b ON h.id = b.hq_id
            GROUP BY h.name
            ORDER BY h.id
        ");

        $x = 0;
        while ($company = $companies->getRow(Recordset::FETCH_ASSOC)) {
            $selected = $x == 0 ? true : false;
            if ($company['hasBranch']) {
                $node = '{id: "' . $company['id'] . '", text: "' . $company['name'] . '"' . $selected . ', item: [';
                $branches = $db->select("
                    SELECT id, name
                    FROM ub_companies_branch
                    WHERE hq_id = ".$company['id']."
                    ORDER BY id
                ");
                while ($branch = $branches->getRow(Recordset::FETCH_ASSOC)) {
                    $node_items[] = $this->createTreeItems($company['id'], $company['name'], false);
                }
                $node .= "\n\t\t" . implode(",\n\t\t",$node_items) . "\n\t";
                $node .= ']}';
                $items[] = $node;
            } else {
                $items[] = $this->createTreeItems($company['id'], $company['name'], $selected);
                $x++;
            }
        }

        $json = '{id: 0, item: [' .
            "\n\t" . implode(",\n\t", $items) . "\n" .
            ']}';

        header('Content-Type: application/json');
        echo($json);
    }

    private function createTreeItems($id, $name, $selected)
    {
        $sel = $selected ? ', select:"1"' : '';
        return '{id: "' . $id . '", text: "' . $name . '"' . $sel . '}';
    }

    /**
     * Task to load the tabbar items.
     */
    public function loadTabs()
    {
        $json = '{
            tabs: [
                {
                    id: "a1",      // tab id
                    text: "Products",    // tab text
                    width: null,      // numeric for tab width or null for auto, optional
                    index: null,      // numeric for tab index or null for last position, optional
                    active: true,      // boolean, make tab active after adding, optional
                    enabled: true,     // boolean, false to disable tab on init
                    close: false       // boolean, render close button on tab, optional
                },
                {
                    id: "a2",      // tab id
                    text: "Logs",    // tab text
                    width: null,      // numeric for tab width or null for auto, optional
                    index: null,      // numeric for tab index or null for last position, optional
                    active: false,      // boolean, make tab active after adding, optional
                    enabled: true,     // boolean, false to disable tab on init
                    close: false,       // boolean, render close button on tab, optional
                    href: "?mod=logs"
                }
            ]
        }';

        header('Content-Type: application/json');
        echo($json);
    }

}
