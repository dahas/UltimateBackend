<?php

namespace UltimateBackend\app\php;

function autoloader($class)
{
    require str_replace(__NAMESPACE__, '', __DIR__) . $class . '.php';
}

spl_autoload_register(__NAMESPACE__ . '\autoloader');

use UltimateBackend\lib\interfaces\Application;
use UltimateBackend\lib\Base;
use UltimateBackend\lib\Modules;
use UltimateBackend\lib\Template;


class App implements Application
{
    private $Template = null;
    private $config = array();

    public function __construct()
    {
        $this->Template = Template::load("app/template/app.html");
        $this->config = Base::getConfig();
    }

    public function render($content)
    {
        $marker['###CONFIG_LANG###'] = $this->config['page_settings']['html_lang'];
        $marker['###CONFIG_TITLE###'] = $this->config['page_settings']['Title'];
        $marker['###CONFIG_CHARSET###'] = $this->config['page_settings']['meta_charset'];

        $marker['###CONFIG_VIEWPORT###'] = $this->config['metatags']['Viewport'];
        $marker['###CONFIG_DESCRIPTION###'] = $this->config['metatags']['Description'];
        $marker['###CONFIG_AUTHOR###'] = $this->config['metatags']['Author'];

        $SubMetaTags = $this->Template->getSubpart('###CONF_METATAGS###');
        $subpart['###CONF_METATAGS###'] = '';
        if (isset($this->config['metatags'])) {
            foreach ($this->config['metatags'] as $meta => $cont) {
                $metaMarker['###NAME###'] = $meta;
                $metaMarker['###CONTENT###'] = $cont;
                $subpart['###CONF_METATAGS###'] .= $SubMetaTags->parse($metaMarker);
            }
        }

        $SubJsFiles = $this->Template->getSubpart('###CONF_JS_FILES###');
        $subpart['###CONF_JS_FILES###'] = '';
        if (isset($this->config['additional_files']['js'])) {
            foreach ($this->config['additional_files']['js'] as $file) {
                $jsMarker['###FILE###'] = $file;
                $subpart['###CONF_JS_FILES###'] .= $SubJsFiles->parse($jsMarker);
            }
        }

        $SubCSSFiles = $this->Template->getSubpart('###CONF_CSS_FILES###');
        $subpart['###CONF_CSS_FILES###'] = '';
        if (isset($this->config['additional_files']['css'])) {
            foreach ($this->config['additional_files']['css'] as $file) {
                $cssMarker['###FILE###'] = $file;
                $subpart['###CONF_CSS_FILES###'] .= $SubCSSFiles->parse($cssMarker);
            }
        }

        $marker['###CONTENT###'] = $content;

        return $this->Template->parse($marker, $subpart);
    }

    public function execute()
    {
        $props = Base::parseQueryString();
        $modName = isset($props['mod']) ? $props['mod'] : 'demo';
        $nowrap = isset($props['nowrap']) ? $props['nowrap'] : 0;

        $Module = Modules::factory($modName, $props);
        $content = $Module->render();

        echo $nowrap ? $content : $this->render($content);
    }
}
