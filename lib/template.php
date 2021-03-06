<?php

namespace UltimateBackend\lib;


/**
 * Class Template
 * @package UltimateBackend\lib
 */
class Template
{
    private $html = "";

    public function __construct($file = '')
    {
        if ($file)
            $this->loadFile($file);
    }

    /**
     * @param $file
     * @return Template
     */
    public static function load($file)
    {
        return new Template($file);
    }

    /**
     * @param $html
     * @return Template
     */
    public static function html($html)
    {
        $tmpl = new Template();
        $tmpl->html = $html;
        return $tmpl;
    }

    private function loadFile($file = '')
    {
        if (is_file($file))
            $this->html = file_get_contents($file);
        else
            $this->html = Tools::errorMessage("No valid template file: $file");
    }

    public function getSubpart($marker)
    {
        $start = strpos($this->html, $marker);
        if ($start === false) {
            return '';
        }
        $start += strlen($marker);
        $stop = strpos($this->html, $marker, $start);
        if ($stop === false) {
            return '';
        }
        $html = substr($this->html, $start, $stop - $start);

        $matches = array();
        if (preg_match('/^([^\<]*\-\-\>)(.*)(\<\!\-\-[^\>]*)$/s', $html, $matches) === 1 || preg_match('/^([^\<]*\-\-\>)(.*)$/s', $html, $matches) === 1) {
            return self::html($matches[2]);
        }
        $matches = array();
        if (preg_match('/(.*)(\<\!\-\-[^\>]*)$/s', $html, $matches) === 1) {
            return self::html($matches[1]);
        }
        return self::html($html);
    }

    private function replaceMarker($html, $markerArr)
    {
        foreach ($markerArr as $key => $val) {
            $html = str_replace($key, $val, $html);
        }
        return $html;
    }

    private function replaceSubpart($html, $subpartArr)
    {
        foreach ($subpartArr as $key => $val) {
            $html = self::trimSubpart($html, $key, $val);
        }
        return $html;
    }

    private static function trimSubpart($html, $marker, $subpart, $recursive = 1)
    {
        $start = strpos($html, $marker);
        if ($start === false) {
            return $html;
        }
        $startAM = $start + strlen($marker);
        $stop = strpos($html, $marker, $startAM);
        if ($stop === false) {
            return $html;
        }
        $stopAM = $stop + strlen($marker);
        $before = substr($html, 0, $start);
        $after = substr($html, $stopAM);
        $between = substr($html, $startAM, $stop - $startAM);
        if ($recursive) {
            $after = self::trimSubpart($after, $marker, $subpart, $recursive);
        }
        $matches = array();
        if (preg_match('/^(.*)\<\!\-\-[^\>]*$/s', $before, $matches) === 1) {
            $before = $matches[1];
        }
        if (is_array($subpart)) {
            $matches = array();
            if (preg_match('/^([^\<]*\-\-\>)(.*)(\<\!\-\-[^\>]*)$/s', $between, $matches) === 1) {
                $between = $matches[2];
            } elseif (preg_match('/^(.*)(\<\!\-\-[^\>]*)$/s', $between, $matches) === 1) {
                $between = $matches[1];
            } elseif (preg_match('/^([^\<]*\-\-\>)(.*)$/s', $between, $matches) === 1) {
                $between = $matches[2];
            }
        }
        $matches = array();
        if (preg_match('/^[^\<]*\-\-\>(.*)$/s', $after, $matches) === 1) {
            $after = $matches[1];
        }
        if (is_array($subpart)) {
            $between = $subpart[0] . $between . $subpart[1];
        } else {
            $between = $subpart;
        }
        return $before . $between . $after;
    }

    public function parse($markerArr = array(), $subpartArr = array())
    {
        $html = $this->html;
        if (!empty($markerArr)) {
            $html = $this->replaceMarker($html, $markerArr);
        }
        if (!empty($subpartArr)) {
            $html = $this->replaceSubpart($html, $subpartArr);
        }
        return $html;
    }

    public static function replaceBodyTag($bt, $tmpl)
    {
        return $tmpl = str_replace("<body>", $bt, $tmpl);
    }

}
