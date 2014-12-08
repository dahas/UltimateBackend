<?php

namespace UltimateBackend\app\php;

function autoloader($class)
{
    require str_replace(__NAMESPACE__, '', __DIR__) . strtolower($class) . '.php';
}

spl_autoload_register(__NAMESPACE__ . '\autoloader');

use UltimateBackend\lib\Tools;
use UltimateBackend\lib\Module;
use UltimateBackend\lib\Template;


class App extends Module
{
    public function __construct()
    {
        Tools::initConfig(); // Loads config.ini
        /*
        * Tools::parseQueryString() creates an array of the URI elements, and filters its values.
        * For security reasons always use the $this->_get member variable instead of directly accessing
        * any $_GET or $_REQUEST parameter.
        * Template::load()lLoads the html file and creates an template object.
        */
        parent::__construct(
            Tools::parseQueryString(),
            Template::load("app/template/app.html")
        );
    }

    /**
     * Output starts with this method.
     */
    public function execute()
    {
        $modName = isset($this->_get['mod']) ? $this->_get['mod'] : 'layout'; // Parameter "mod" is the required module name.
        $nowrap = isset($this->_get['nowrap']) ? true : false; // Parameter "nowrap" displays module as it is, without wrapping it into app html. (Optional)
        $task = isset($this->_get['task']) ? $this->_get['task'] : "render"; // Parameter "task" is required, so that the module knows, which task to execute.

        $Module = Module::create($modName, $this->_get); // The factory pattern returns an object of a module.
        $html = $Module->$task(); // The module executes the requested task.

        if ($nowrap || $task != "render")
            $appHtml = $html;
        else {
            $appHtml = $this->render($html);
        }

        return $appHtml;
    }

    /**
     * @param $html
     * @return mixed|string
     */
    public function render($html = "")
    {
        $additionalFiles = Tools::getHeaderFiles();
        $bodyOnload = Tools::getBodyOnload();
        $marker['###MOD_LAYOUT###'] = $html;

        $marker['###CONFIG_LANG###'] = $this->config['page_settings']['HTML_Lang'];
        #$marker['###CONFIG_TITLE###'] = $this->config['page_settings']['Title'];
        $marker['###CONFIG_TITLE###'] = "Memory: " . round(memory_get_usage() / 1024, 2) . " KB";
        $marker['###CONFIG_CHARSET###'] = $this->config['page_settings']['Meta_Charset'];

        $marker['###CONFIG_VIEWPORT###'] = $this->config['metatags']['Viewport'];
        $marker['###CONFIG_DESCRIPTION###'] = $this->config['metatags']['Description'];
        $marker['###CONFIG_AUTHOR###'] = $this->config['metatags']['Author'];

        $SubBodyTag = $this->Template->getSubpart('###BODY_ONLOAD###');
        $subpart['###BODY_ONLOAD###'] = '';
        if ($bodyOnload) {
            $markerBdOl['###ONLOAD###'] = $bodyOnload;
            $subpart['###BODY_ONLOAD###'] = $SubBodyTag->parse($markerBdOl);
        }

        $SubMetaTags = $this->Template->getSubpart('###CONF_METATAGS###');
        $subpart['###CONF_METATAGS###'] = '';
        if (isset($this->config['metatags'])) {
            foreach ($this->config['metatags'] as $meta => $content) {
                $metaMarker['###NAME###'] = $meta;
                $metaMarker['###CONTENT###'] = $content;
                $subpart['###CONF_METATAGS###'] .= $SubMetaTags->parse($metaMarker);
            }
        }

        $SubJsFiles = $this->Template->getSubpart('###CONF_JS_FILES###');
        $subpart['###CONF_JS_FILES###'] = '';
        if (isset($additionalFiles['js'])) {
            foreach ($additionalFiles['js'] as $file) {
                $jsMarker['###FILE###'] = $file;
                $subpart['###CONF_JS_FILES###'] .= $SubJsFiles->parse($jsMarker);
            }
        }

        $SubCSSFiles = $this->Template->getSubpart('###CONF_CSS_FILES###');
        $subpart['###CONF_CSS_FILES###'] = '';
        if (isset($additionalFiles['css'])) {
            foreach ($additionalFiles['css'] as $file) {
                $cssMarker['###FILE###'] = $file;
                $subpart['###CONF_CSS_FILES###'] .= $SubCSSFiles->parse($cssMarker);
            }
        }

        return $this->Template->parse($marker, $subpart);
    }

    public function __destruct()
    {
        unset($this->Template);
        unset($this->DB);
        unset($this);
    }
}
