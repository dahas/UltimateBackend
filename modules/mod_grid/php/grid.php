<?php

use UltimateBackend\lib\interfaces\Module;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\Base;


class Grid implements Module
{
    private $properties = array();
    private $Template = null;

    public $additional_files = array();

    public $data_link = "?mod=grid&task=loadData";

    public function __construct($props, Template $Tmpl = null)
    {
        $this->Template = $Tmpl ? $Tmpl : Template::load("modules/mod_grid/template/grid.html");
        $this->properties = $props;

        Base::setHeaderFiles(array(
            'css' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css"
            ),
            'js' => array(
                "resources/dhtmlxSuite_v403_std/sources/dhtmlxGrid/codebase/dhtmlxgrid.js"
            )
        ));
    }

    /**
     * @return string HTML
     */
    public function render()
    {
        $marker['###DATA_URL###'] = $this->data_link;
        return $this->Template->parse($marker);
    }

    /**
     * Task to load data
     */
    public function loadData()
    {
        $data = '{
            rows: [
                { id: 1001,
                    data: [
                        "100",
                        "A Time to Kill",
                        "John Grisham",
                        "12.99",
                        "1",
                        "05/01/1998"] },
                { id: 1002,
                    data: [
                        "1000",
                        "Blood and Smoke",
                        "Stephen King",
                        "0",
                        "1",
                        "01/01/2000"] },
                { id: 1003,
                    data: [
                        "-200",
                        "The Rainmaker",
                        "John Grisham",
                        "7.99",
                        "0",
                        "12/01/2001"] },
                { id: 1004,
                    data: [
                        "350",
                        "The Green Mile",
                        "Stephen King",
                        "11.10",
                        "1",
                        "01/01/1992"] },
                { id: 1005,
                    data: [
                        "700",
                        "Misery",
                        "Stephen King",
                        "7.70",
                        "0",
                        "01/01/2003"] },
                { id: 1006,
                    data: [
                        "-1200",
                        "The Dark Half",
                        "Stephen King",
                        "0",
                        "0",
                        "10/30/1999"] },
                { id: 1007,
                    data: [
                        "1500",
                        "The Partner",
                        "John Grisham",
                        "12.99",
                        "1",
                        "01/01/2005"] },
                { id: 1008,
                    data: [
                        "500",
                        "It",
                        "Stephen King",
                        "9.70",
                        "0",
                        "10/15/2001"] },
                { id: 1009,
                    data: [
                        "400",
                        "Cousin Bette",
                        "Honore de Balzac",
                        "0",
                        "1",
                        "12/01/1991"] },
                { id: 10010,
                    data: [
                        "400",
                        "Cousin Bette",
                        "Honore de Balzac",
                        "0",
                        "1",
                        "12/01/1991"] },
                { id: 10910,
                    data: [
                        "400",
                        "Cousin Bette",
                        "Honore de Balzac",
                        "0",
                        "1",
                        "12/01/1991"] }
            ]
        };';

        header('Content-Type: application/json');
        echo($data);
    }

}
