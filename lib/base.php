<?php

namespace UltimateBackend\lib;


class Base
{
    private static $iniFile = "config.ini";
    private static $bodyOnload = '';

    /**
     * @return array
     * @throws \Exception
     */
    public static function getConfig()
    {
        return parse_ini_file(self::$iniFile, true);
    }

    /**
     * @return array
     */
    public static function parseQueryString()
    {
        $varArr = array();
        if (isset($_SERVER['QUERY_STRING'])) {
            $qs = $_SERVER['QUERY_STRING'];
            $vars = explode("&", $qs);
            foreach ($vars as $var) {
                $parts = explode("=", $var);
                if (count($parts) == 2)
                    $varArr[$parts[0]] = self::filterInput($parts[1]);
                else
                    $varArr[$parts[0]] = "";
            }
        }
        return $varArr;
    }

    public static function setBodyOnload($value)
    {
        self::$bodyOnload .= $value;
    }

    public static function getBodyOnload()
    {
        return self::$bodyOnload;
    }

    /**
     * @param $input
     * @return mixed
     */
    private static function filterInput($input)
    {
        return filter_var(rawurldecode($input), FILTER_SANITIZE_STRING);
    }

    /**
     * @param $msg
     * @return string
     */
    public static function errorMessage($msg)
    {
        return '<span class="error_message">' . $msg . '</span>';
    }

}
