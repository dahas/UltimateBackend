<?php

namespace UltimateBackend\lib;


/**
 * Abstract Class Module
 * @package UltimateBackend\lib\interfaces
 */
abstract class Module
{
    protected $_get = array();
    protected $config = array();
    protected $Template = null;
    protected $DB = null;

    public function __construct($_get, Template $Tmpl = null)
    {
        $this->_get = $_get;
        $this->Template = $Tmpl;

        $this->config = Tools::getConfig();

        $this->DB = DB::getInstance(
            $this->config['database']['DB_Name'],
            $this->config['database']['Host'],
            $this->config['database']['Username'],
            $this->config['database']['Password'],
            $this->config['database']['Charset']
        );
    }

    /**
     * @return object
     * @throws exception
     */
    public static function create()
    {
        $args = func_get_args();
        $types = array();
        foreach ($args as $arg) {
            $types[] = gettype($arg);
        }

        // First argument must be the module name:
        if (!isset($args[0]) || $types[0] != "string" || !$args[0]) {
            throw new exception('Not a valid classname!');
        } else {
            $name = $args[0];
        }

        $properties = array();
        $Tmpl = null;

        // If 2nd argument exists
        if (count($args) == 2) {
            if ($types[1] == "object" && get_class($args[1]) == "Template") {
                $Tmpl = $args[1];
            } else {
                $properties = $args[1];
            }
        } // If 3rd argument exists
        else if (count($args) >= 3) {
            if ($types[1] == "object" && get_class($args[1]) == "Template") {
                $Tmpl = $args[1];
                $properties = $args[2];
            } else {
                $properties = $args[1];
                $Tmpl = $args[2];
            }
        }

        $modFile = "modules/mod_$name/php/$name.php";

        if (is_file($modFile)) {
            require_once $modFile;
            return new $name($properties, $Tmpl);
        } else {
            return new ErrorMod($name);
        }
    }

    abstract public function render($html = "");

    public function __call($method, $args)
    {
        return Tools::errorMessage("Method $method() not defined!");
    }

    public function __destruct()
    {
        unset($this->Template);
        unset($this->DB);
        unset($this);
    }
}


/**
 * Class ErrorMod
 * @package UltimateBackend\lib
 */
class ErrorMod
{
    private $modName = "";

    public function __construct($modName)
    {
        $this->modName = strtolower("mod_$modName");
    }

    public function render()
    {
        return Tools::errorMessage("Module '$this->modName' not found!");
    }
}