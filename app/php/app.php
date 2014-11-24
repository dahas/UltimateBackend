<?php

namespace UltimateBackend\app\php;

function autoloader($class)
{
    require str_replace(__NAMESPACE__, '', __DIR__) . strtolower($class) . '.php';
}

spl_autoload_register(__NAMESPACE__ . '\autoloader');

use UltimateBackend\lib\interfaces\Application;
use UltimateBackend\lib\Base;
use UltimateBackend\lib\Modules;
use UltimateBackend\lib\Template;


class App implements Application
{
    private $config = array();
    private $properties = array();
    private $Template = null;
    public $html = '';

    public function __construct()
    {
        $this->Template = Template::load("app/template/app.html"); // Loads the html file and creates an template object.
        $this->config = Base::getConfig(); // Reads base configuration from config.ini.
    }

    /**
     * Everything starts with this method.
     */
    public function execute()
    {
        /*
         * Base::parseQueryString() creates an array of the URI elements, and is filtering its values.
         * For security reasons always use this function instead of directly accessing $_GET or $_REQUEST parameters!
         */
        $this->properties = Base::parseQueryString();

        if(isset($this->properties['mod']) && strtolower($this->properties['mod']) == "layout")
            $appHtml = $this->render(Base::errorMessage("Please check the configuration of the layout module: You created an infinite loop!"));
        else {
            $modName = isset($this->properties['mod']) ? $this->properties['mod'] : 'layout'; // Parameter "mod" is the required module name.
            $nowrap = isset($this->properties['nowrap']) ? true : false; // Parameter "nowrap" displays module as is, without wrapping it into app html. (Optional)

            $Module = Modules::factory($modName, $this->properties); // The factory pattern returns an object of a module.
            $html = $Module->render();

            /* Merge CSS and JS files and add them to the html header. */
            $additionalFiles = array_merge_recursive($this->config['additional_files'], $Module->additionalFiles);

            if ($nowrap)
                $appHtml = $html;
            else {
                $appHtml = $this->render($html, $additionalFiles);
            }
        }

        echo $appHtml;
    }

    /**
     * This method returns the parsed html.
     * @param $html
     * @param array $additionalFiles
     * @return mixed|string
     */
    public function render($html, $additionalFiles = array())
    {
        $marker['###MOD_LAYOUT###'] = $html;

        $marker['###CONFIG_LANG###'] = $this->config['page_settings']['html_lang'];
        $marker['###CONFIG_TITLE###'] = $this->config['page_settings']['Title'];
        $marker['###CONFIG_CHARSET###'] = $this->config['page_settings']['meta_charset'];

        $marker['###CONFIG_VIEWPORT###'] = $this->config['metatags']['Viewport'];
        $marker['###CONFIG_DESCRIPTION###'] = $this->config['metatags']['Description'];
        $marker['###CONFIG_AUTHOR###'] = $this->config['metatags']['Author'];

        $marker['###BODY_ONLOAD###'] = Base::getBodyOnload();

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
}
