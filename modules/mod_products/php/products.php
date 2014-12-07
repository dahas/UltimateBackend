<?php

use UltimateBackend\lib\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Tools;
use UltimateBackend\lib\Recordset;


class Products extends Module
{
    public function __construct($_get, Template $Tmpl = null)
    {
        Module::__construct($_get, $Tmpl);

        if (!$this->Template)
            $this->Template = Template::load("modules/mod_products/template/products.html");

        Tools::setHeaderFiles(array(
            'css' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css"
            ),
            'js' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"
            )
        ));
    }

    /**
     * Task to render HTML
     * @return string
     */
    public function render()
    {
        $ModGrid = Module::create("Grid");
        $ModGrid->setDataLink("?mod=products&task=loadProducts&id=" . $this->get['id']);
        $ModGrid->setColumnHeaders("Title,Price,On Stock,");
        $ModGrid->setColumnWidths("200,80,80,*");
        $ModGrid->setColumnAlign("left,right,right,left");
        $ModGrid->setColumnTypes("ed,ed,ed,ro");
        $ModGrid->setColumnSorting("str,int,int,int");
        $ModGrid->setFooter("Footer asdasda ad asdas asd adas dsad,#cspan,#cspan,#cspan");
        $marker['###MOD_GRID###'] = $ModGrid->render();

        $marker['###ADD_PRODUCT_URL###'] = "?mod=products&task=addProduct&id=" . $this->get['id'];
        $marker['###EDIT_PRODUCT_URL###'] = "?mod=products&task=editProduct&id=" . $this->get['id'];
        $marker['###DELETE_PRODUCTS_URL###'] = "?mod=products&task=deleteProducts&id=" . $this->get['id'];

        return $this->Template->parse($marker);
    }

    /**
     * Task to load data
     */
    public function loadProducts()
    {
        $products = $this->DB->select([
            "columns" => "*",
            "from" => "ub_products",
            "where" => "manufacturer_id=" . $this->get['id']
        ]);

        $data = array();
        while ($product = $products->getRow(Recordset::FETCH_ASSOC)) {
            $data[] = '{id: ' . $product['id'] . ', data: ["' . $product['title'] . '","' . $product['price'] . '","' . $product['amount'] . '"]}';
        }
        $json = '{rows: [' . implode(",", $data) . ']};';

        header('Content-Type: application/json');
        echo($json);
    }

    /**
     * Task to insert a row
     */
    public function addProduct()
    {
        $insertID = $this->DB->insert([
            "into" => "ub_products",
            "columns" => "manufacturer_id",
            "values" => "{$this->get['id']}"
        ]);
        echo $insertID;
    }

    /**
     * Task to edit a row
     */
    public function editProduct()
    {
        echo $this->DB->update([
            "table" => "ub_products",
            "set" => "{$this->get['fName']}='{$this->get['fValue']}'",
            "where" => "manufacturer_id={$this->get['id']} AND id={$this->get['rid']}"
        ]);
    }

    /**
     * Task to delete rows
     */
    public function deleteProducts()
    {
        $rids = $this->get['rids'];
        echo $this->DB->delete([
            "from" => "ub_products",
            "where" => " id IN($rids)"
        ]);
    }

}
