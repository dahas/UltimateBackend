<?php

namespace UltimateBackend\lib;


class Template
{
    private $html = "";

    public function __construct($file = '')
    {
        if (is_file($file))
            $this->html = file_get_contents($file);
        else
            $this->html = Base::errorMessage("No valid template file!");
    }

    public static function load($file)
    {
        return new Template($file);
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
        $tmpl = new Template();
        $matches = array();
        if (preg_match('/^([^\<]*\-\-\>)(.*)(\<\!\-\-[^\>]*)$/s', $html, $matches) === 1) {
            $tmpl->html = $matches[2];
            return $tmpl;
        }
        $matches = array();
        if (preg_match('/(.*)(\<\!\-\-[^\>]*)$/s', $html, $matches) === 1) {
            $tmpl->html = $matches[1];
            return $tmpl;
        }
        $matches = array();
        if (preg_match('/^([^\<]*\-\-\>)(.*)$/s', $html, $matches) === 1) {
            $tmpl->html = $matches[2];
            return $tmpl;
        }
        $tmpl->html = $html;
        return $tmpl;
    }

    private function substituteMarker($html, $markerArray)
    {
        foreach ($markerArray as $key => $val) {
            $html = str_replace($key, $val, $html);
        }
        return $html;
    }

    private function substituteSubpart($html, $subpartArray)
    {
        foreach ($subpartArray as $key => $val) {
            $html = self::trimSubpart($html, $key, $val);
        }
        return $html;
    }

    public static function trimSubpart($html, $marker, $subpartContent, $recursive = 1)
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
            $after = self::trimSubpart($after, $marker, $subpartContent, $recursive);
        }
        $matches = array();
        if (preg_match('/^(.*)\<\!\-\-[^\>]*$/s', $before, $matches) === 1) {
            $before = $matches[1];
        }
        if (is_array($subpartContent)) {
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
        if (is_array($subpartContent)) {
            $between = $subpartContent[0] . $between . $subpartContent[1];
        } else {
            $between = $subpartContent;
        }
        return $before . $between . $after;
    }

    public function parse($markerArray = array(), $subpartArray = array())
    {
        $html = $this->html;
        if (!empty($markerArray)) {
            $html = $this->substituteMarker($html, $markerArray);
        }
        if (!empty($subpartArray)) {
            $html = $this->substituteSubpart($html, $subpartArray);
        }
        return $html;
    }

    public static function replaceBodyTag($bt, $tmpl)
    {
        return $tmpl = str_replace("<body>", $bt, $tmpl);
    }

}
