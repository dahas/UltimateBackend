<?php

namespace UltimateBackend\lib;

class Modules
{
    static public function factory($name, $properties = array())
    {
        if (!is_string($name) || !strlen($name)) {
            throw new exception('Not a valid classname!');
        }
        require_once "modules/mod_$name/php/$name.php";
        return new $name($properties);
    }
}