<?php

namespace UltimateBackend\lib;

class Modules
{
    /**
     * @return object
     * @throws exception
     */
    static public function factory()
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

        $properties = null;
        $Tmpl = null;

        // If 2nd argument exists
        if (count($args) == 2) {
            if ($types[1] != "array") {
                $Tmpl = $args[1];
            } else {
                $properties = $args[1];
            }
        } // If 3rd argument exists
        else if (count($args) >= 3) {
            if ($types[1] != "array") {
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
}

class ErrorMod
{
    private $modName = "";

    public function __construct($modName)
    {
        $this->modName = strtolower("mod_$modName");
    }

    public function render()
    {
        return Base::errorMessage("Module '$this->modName' not found!");
    }
}