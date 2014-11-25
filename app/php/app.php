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
    private $Template = null;

    public function __construct()
    {
        Base::construct();
        $this->Template = Template::load("app/template/app.html"); // Loads the html file and creates an template object.
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
        $_get = Base::parseQueryString();

        if(isset($_get['mod']) && strtolower($_get['mod']) == "layout")
            $appHtml = $this->render(Base::errorMessage("Please check the configuration of the layout module: You created an infinite loop!"));
        else {
            $modName = isset($_get['mod']) ? $_get['mod'] : 'layout'; // Parameter "mod" is the required module name.
            $nowrap = isset($_get['nowrap']) ? true : false; // Parameter "nowrap" displays module as is, without wrapping it into app html. (Optional)

            $Module = Modules::factory($modName, $_get); // The factory pattern returns an object of a module.
            $html = $Module->render();

            if ($nowrap)
                $appHtml = $html;
            else {
                $appHtml = $this->render($html);
            }
        }

        echo $appHtml;
    }

    /**
     * @param $html
     * @return mixed|string
     */
    public function render($html)
    {
        $config = Base::getConfig();
        $additionalFiles = Base::getHeaderFiles();
        $marker['###MOD_LAYOUT###'] = $html;

        $marker['###CONFIG_LANG###'] = $config['page_settings']['html_lang'];
        $marker['###CONFIG_TITLE###'] = $config['page_settings']['Title'];
        $marker['###CONFIG_CHARSET###'] = $config['page_settings']['meta_charset'];

        $marker['###CONFIG_VIEWPORT###'] = $config['metatags']['Viewport'];
        $marker['###CONFIG_DESCRIPTION###'] = $config['metatags']['Description'];
        $marker['###CONFIG_AUTHOR###'] = $config['metatags']['Author'];

        $marker['###BODY_ONLOAD###'] = Base::getBodyOnload();

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
}
