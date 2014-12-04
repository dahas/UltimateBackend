<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;
use UltimateBackend\lib\Modules;
use UltimateBackend\lib\Recordset;


class Products extends Module
{
    public function __construct($props, Template $Tmpl = null)
    {
        Module::__construct($props, $Tmpl);

        if (!$this->Template)
            $this->Template = Template::load("modules/mod_products/template/products.html");

        Base::setHeaderFiles(array(
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
        $ModGrid = Modules::factory("Grid");
        $ModGrid->setDataLink("?mod=products&task=loadProducts&id=" . $this->properties['id']);
        $ModGrid->setColumnHeaders("Title,Price,On Stock,");
        $ModGrid->setColumnWidths("200,80,80,*");
        $ModGrid->setColumnAlign("left,right,right,left");
        $ModGrid->setColumnTypes("ed,ed,ed,ro");
        $ModGrid->setColumnSorting("str,int,int,int");
        $ModGrid->setFooter("Footer asdasda ad asdas asd adas dsad,#cspan,#cspan,#cspan");
        $marker['###MOD_GRID###'] = $ModGrid->render();

        $marker['###ADD_PRODUCT_URL###'] = "?mod=products&task=addProduct&id=" . $this->properties['id'];
        $marker['###EDIT_PRODUCT_URL###'] = "?mod=products&task=editProduct&id=" . $this->properties['id'];
        $marker['###DELETE_PRODUCTS_URL###'] = "?mod=products&task=deleteProducts&id=" . $this->properties['id'];

        return $this->Template->parse($marker);
    }

    /**
     * Task to load data
     */
    public function loadProducts()
    {
        $products = $this->DB->select(array(
            "columns" => "*",
            "from" => "ub_products",
            "where" => "manufacturer_id=" . $this->properties['id']
        ));

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
        $insertID = $this->DB->insert(array(
            "into" => "ub_products",
            "columns" => "manufacturer_id",
            "values" => "{$this->properties['id']}"
        ));
        echo $insertID;
    }

    /**
     * Task to edit a row
     */
    public function editProduct()
    {
        echo $this->DB->update(array(
            "table" => "ub_products",
            "set" => "{$this->properties['fName']}='{$this->properties['fValue']}'",
            "where" => "manufacturer_id={$this->properties['id']} AND id={$this->properties['rid']}"
        ));
    }

    /**
     * Task to delete rows
     */
    public function deleteProducts()
    {
        $rids = $this->properties['rids'];
        echo $this->DB->delete(array(
            "from" => "ub_products",
            "where" => " id IN($rids)"
        ));
    }

}
