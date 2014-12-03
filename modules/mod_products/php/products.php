<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;
use UltimateBackend\lib\Modules;
use UltimateBackend\lib\DB;
use UltimateBackend\lib\Recordset;


class Products implements Module
{
    private $properties = array();
    private $Template = null;

    private $db = null;

    public function __construct($props, Template $Tmpl = null)
    {
        $this->Template = $Tmpl ? $Tmpl : Template::load("modules/mod_products/template/products.html");
        $this->properties = $props;

        if(isset($this->properties['task']))
            $this->db = DB::connect();

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
     * @return string HTML
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
        $data = array();

        $products = $this->db->select("
            SELECT * FROM ub_products WHERE manufacturer_id={$this->properties['id']}
        ");

        while($product = $products->getRow(Recordset::FETCH_ASSOC)) {
            $data[] = '{id: '.$product['id'].', data: ["'.utf8_encode($product['title']).'","'.$product['price'].'","'.$product['amount'].'"]}';
        }
        $json = '{rows: ['.implode(",", $data).']};';

        header('Content-Type: application/json');
        echo($json);
    }

    /**
     * Task to insert a row
     */
    public function addProduct()
    {
        echo $this->db->insert("INSERT INTO ub_products SET manufacturer_id={$this->properties['id']}");
    }

    /**
     * Task to edit a row
     */
    public function editProduct()
    {
        $value = utf8_decode($this->properties['fValue']);
        echo $this->db->update("UPDATE ub_products SET {$this->properties['fName']}='$value' WHERE manufacturer_id={$this->properties['id']} AND id={$this->properties['rid']}");
    }

    /**
     * Task to delete rows
     */
    public function deleteProducts()
    {
        $rids = explode(",", $this->properties['rids']);
        $del = array();
        foreach($rids as $rid) {
            if($this->db->delete("DELETE FROM ub_products WHERE manufacturer_id={$this->properties['id']} AND ID=$rid"))
                $del[] = $rid;
        }
        echo implode(",",$del);
    }

}
