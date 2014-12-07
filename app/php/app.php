<?php

namespace UltimateBackend\app\php;

function autoloader($class)
{
    require str_replace(__NAMESPACE__, '', __DIR__) . strtolower($class) . '.php';
}

spl_autoload_register(__NAMESPACE__ . '\autoloader');

use UltimateBackend\lib\interfaces\Application;
use UltimateBackend\lib\Tools;
use UltimateBackend\lib\Module;
use UltimateBackend\lib\Template;


class App implements Application
{
    private $Template = null;

    public function __construct()
    {
        Tools::initConfig(); // Loads config.ini
        $this->Template = Template::load("app/template/app.html"); // Loads the html file and creates an template object.
    }

    /**
     * Output starts with this method.
     */
    public function execute()
    {
        /*
        * Base::parseQueryString() creates an array of the URI elements, and is filtering its values.
        * For security reasons always use this function instead of directly accessing $_GET or $_REQUEST parameters!
        */
        $_get = Tools::parseQueryString();

        $modName = isset($_get['mod']) ? $_get['mod'] : 'layout'; // Parameter "mod" is the required module name.
        $nowrap = isset($_get['nowrap']) ? true : false; // Parameter "nowrap" displays module as it is, without wrapping it into app html. (Optional)
        $task = isset($_get['task']) ? $_get['task'] : "render"; // Parameter "task" is required, so that the module knows, which task to execute.

        $Module = Module::create($modName, $_get); // The factory pattern returns an object of a module.
        $html = $Module->$task(); // The module executes the requested task.

        if ($nowrap || $task != "render")
            $appHtml = $html;
        else {
            $appHtml = $this->wrapIntoApp($html);
        }

        return $appHtml;
    }

    /**
     * @param $html
     * @return mixed|string
     */
    public function wrapIntoApp($html)
    {
        $config = Tools::getConfig();
        $additionalFiles = Tools::getHeaderFiles();
        $bodyOnload = Tools::getBodyOnload();
        $marker['###MOD_LAYOUT###'] = $html;

        $marker['###CONFIG_LANG###'] = $config['page_settings']['HTML_Lang'];
        #$marker['###CONFIG_TITLE###'] = $config['page_settings']['Title'];
        $marker['###CONFIG_TITLE###'] = "Memory: " . round(memory_get_usage()/1024,2) . " KB";
        $marker['###CONFIG_CHARSET###'] = $config['page_settings']['Meta_Charset'];

        $marker['###CONFIG_VIEWPORT###'] = $config['metatags']['Viewport'];
        $marker['###CONFIG_DESCRIPTION###'] = $config['metatags']['Description'];
        $marker['###CONFIG_AUTHOR###'] = $config['metatags']['Author'];

        $SubBodyTag = $this->Template->getSubpart('###BODY_ONLOAD###');
        $subpart['###BODY_ONLOAD###'] = '';
        if ($bodyOnload) {
            $markerBdOl['###ONLOAD###'] = $bodyOnload;
            $subpart['###BODY_ONLOAD###'] = $SubBodyTag->parse($markerBdOl);
        }

        $SubMetaTags = $this->Template->getSubpart('###CONF_METATAGS###');
        $subpart['###CONF_METATAGS###'] = '';
        if (isset($config['metatags'])) {
            foreach ($config['metatags'] as $meta => $content) {
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
        unset($this);
    }
}
