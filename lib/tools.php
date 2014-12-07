<?php

namespace UltimateBackend\lib;


class Tools
{
    private static $iniFile = "config.ini";
    private static $config = array();
    private static $bodyOnload = '';

    public static function initConfig()
    {
        self::$config = parse_ini_file(self::$iniFile, true);
    }

    /**
     * @return array
     */
    public static function getConfig()
    {
        return self::$config;
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

    /**
     * @param array $files
     */
    public static function setHeaderFiles($files = array())
    {
        if (isset($files['css']) || isset($files['js'])) {
            self::$config['additional_files'] = array_merge_recursive(self::$config['additional_files'], $files);
        }
    }

    /**
     * @return array
     */
    public static function getHeaderFiles()
    {
        if(isset(self::$config['additional_files']))
            return self::removeDuplicateFiles(self::$config['additional_files']);
        else
            return array();
    }

    /**
     * @param $array
     * @return array
     */
    private static function removeDuplicateFiles($array)
    {
        $fixedArr = array();
        if(isset($array['css']))
            $fixedArr['css'] = array_unique($array['css']);
        if(isset($array['js']))
            $fixedArr['js'] = array_unique($array['js']);
        return $fixedArr;
    }

    /**
     * @param $value
     */
    public static function setBodyOnload($value)
    {
        self::$bodyOnload .= $value;
    }

    /**
     * @return string
     */
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
