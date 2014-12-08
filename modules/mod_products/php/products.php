<?php

use UltimateBackend\lib\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\DB;
use UltimateBackend\lib\Tools;
use UltimateBackend\lib\Recordset;


class Products extends Module
{
    private $DB = null;

    public function __construct(Template $Tmpl = null)
    {
        parent::__construct($Tmpl);

        if (!$this->Template)
            $this->Template = Template::load("modules/mod_products/template/products.html");

        Tools::setHeaderFiles([
            'css' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css"
            ),
            'js' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"
            )
        ]);

        $this->DB = DB::getInstance(
            $this->config['database']['DB_Name'],
            $this->config['database']['Host'],
            $this->config['database']['Username'],
            $this->config['database']['Password'],
            $this->config['database']['Charset']
        );
    }

    /**
     * Task to render HTML
     * @return string
     */
    public function render($html = "")
    {
        $ModGrid = Module::create("Grid");
        $ModGrid->setDataLink("?mod=products&task=loadProducts&id=" . $this->_get['id']);
        $ModGrid->setColumnHeaders("Title,Price,On Stock,");
        $ModGrid->setColumnWidths("200,80,80,*");
        $ModGrid->setColumnAlign("left,right,right,left");
        $ModGrid->setColumnTypes("ed,ed,ed,ro");
        $ModGrid->setColumnSorting("str,int,int,int");
        $ModGrid->setFooter("123144,#cspan,#cspan,#cspan");
        $marker['###MOD_GRID###'] = $ModGrid->render();

        $marker['###ADD_PRODUCT_URL###'] = "?mod=products&task=addProduct&id=" . $this->_get['id'];
        $marker['###EDIT_PRODUCT_URL###'] = "?mod=products&task=editProduct&id=" . $this->_get['id'];
        $marker['###DELETE_PRODUCTS_URL###'] = "?mod=products&task=deleteProducts&id=" . $this->_get['id'];

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
            "where" => "manufacturer_id=" . $this->_get['id']
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
        echo $this->DB->insert([
            "into" => "ub_products",
            "columns" => "manufacturer_id",
            "values" => "{$this->_get['id']}"
        ]);
    }

    /**
     * Task to edit a row
     */
    public function editProduct()
    {
        echo $this->DB->update([
            "table" => "ub_products",
            "set" => "{$this->_get['fName']}='{$this->_get['fValue']}'",
            "where" => "manufacturer_id={$this->_get['id']} AND id={$this->_get['rid']}"
        ]);
    }

    /**
     * Task to delete rows
     */
    public function deleteProducts()
    {
        $rids = $this->_get['rids'];
        echo $this->DB->delete([
            "from" => "ub_products",
            "where" => " id IN($rids)"
        ]);
    }

}
