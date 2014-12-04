<?php

namespace UltimateBackend\lib\interfaces;

use UltimateBackend\lib\Base;
use UltimateBackend\lib\Template;
use UltimateBackend\lib\DB;


/**
 * Abstract Class Module
 * @package UltimateBackend\lib\interfaces
 */
abstract class Module
{
    protected $properties = array();
    protected $Template = null;
    protected $DB = null;

    abstract public function render();

    public function __construct($props, Template $Tmpl = null)
    {
        $this->properties = $props;
        $this->Template = $Tmpl;

        $config = Base::getConfig();

        $this->DB = new DB(
            $config['database']['DB_Name'],
            $config['database']['Host'],
            $config['database']['Username'],
            $config['database']['Password'],
            $config['database']['Charset']
        );
    }

    public function __call($method, $args)
    {
        return Base::errorMessage("Method $method() not defined!");
    }

    public function __destruct()
    {
        unset($this->Template);
        unset($this->DB);
        unset($this);
    }
}