<?php

namespace CoreBundle;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoreFrontController extends CoreCommonController
{
    public function _error403Action(Request $request)
    {
        return \CoreBundle\Action\Error\Error403Action::GET($this, $request);
    }

    public function _error404Action(Request $request)
    {
        return \CoreBundle\Action\Error\Error404Action::GET($this, $request);
    }

    public function _error500Action(Request $request, $error)
    {
        return \CoreBundle\Action\Error\Error500Action::GET($this, $request, $error);
    }

    public function encodePassword($email, $password)
    {
        return \CoreBundle\Utility\SitePassword::encodePassword($this, $email, $password);
    }

    public function render($view, array $parameters = array(), Response $response = null)
    {
        $siteSettings = $this->_initVariables();

        $request = $this->container->get('request');
        $routeName = $request->get('_route');

        $parameters['regions'] = $this->getPageRegions($routeName);

        $parameters['site_name'] = $this->_variableGet('SITE_TITLE');
        $parameters['page_title'] = $this->_getPageTitle();
        $parameters['meta_tags'] = $this->_getMetaTags();
        $parameters['settings'] = $siteSettings;

        global $scripts;
        if (!isset($scripts['file'])) {
            $scripts['file'] = array();
        }
        if (!isset($scripts['inline'])) {
            $scripts['inline'] = array();
        }

        $parameters['scripts'] = $this->renderView('@front/common/scripts.html.twig', array('scripts' => $scripts, 'settings' => $siteSettings));
        global $styles;
        $parameters['styles'] = $this->renderView('@front/common/styles.html.twig', array('styles' => $styles, 'settings' => $siteSettings));

        if ($this->container->has('templating')) {

            return $this->container->get('templating')->renderResponse($view, $parameters, $response);
        }

        if (!$this->container->has('twig')) {
            throw new \LogicException('You can not use the "render" method if the Templating Component or the Twig Bundle are not available.');
        }

        if (null === $response) {

            $response = new Response();
        }

        $response->setContent($this->container->get('twig')->render($view, $parameters));

        return $response;
    }


    private function getSystemMenus()
    {
        $request = $this->container->get('request');
        $currentPath = $request->getRequestUri();

        $dql = "SELECT m.id, m.name, m.code
        FROM CoreBundle:Menu m
        WHERE m.status = 1";
        $arrMenus = $this->_executeDQL($dql, array());

        $menus = array();
        foreach ($arrMenus as $value) {
            $menuCode = $this->_getArrayValue($value, 'code', '');
            $menuId = $this->_getArrayValue($value, 'id', 0);
            $dql = "SELECT ml.title, ml.path, ml.friendlyPath, ml.type
            FROM CoreBundle:MenuLink ml
            WHERE ml.menuId = :menuId
            ORDER BY ml.weight ASC, ml.id ASC";
            $arr = $this->_executeDQL($dql, array(':menuId' => $menuId));
            $links = array();
            foreach ($arr as $value) {
                $path = $this->_getArrayValue($value, 'path', '');
                $pathUrl = $this->_getSymfonyUrl($path);

                $links[] = array(
                  'title' => $this->_getArrayValue($value, 'title', ''),
                  'link' => $pathUrl,
                  'active' => $currentPath == $pathUrl ? 1 : 0,
                  'type' => $this->_getArrayValue($value, 'type', 0),
                  'items' => array(),
                );

            }
            $menus[$menuCode] = $links;
        }

        return $menus;
    }

    private function getPageRegions($type = 'page', $object_id = 0)
    {
        $menus = $this->getSystemMenus();
        $regions = array();
        $header = '';
        $footer = '';
        $frontThemeId = $this->_getConfigValue('front_theme_id');
        $pathToTheme = $this->_getRootPath().$frontThemeId.'';
        $siteSettings = $this->_initVariables();
        // add header
        $headerFilePath = $pathToTheme.'/config/header.yml';
        $arrInfo = $this->_getYamlValueFromFile($headerFilePath, array());
        $options = $this->_getArrayValue($arrInfo, 'options', array());

        foreach ($options as $value) {

            $headerConfigFile = $pathToTheme.'/config/'.$value;
            $headerData = $this->_getYamlValueFromFile($headerConfigFile);
            $template = $this->_getArrayValue($headerData, 'template');
            $arrCss = $this->_getArrayValue($headerData, 'css', array());
            foreach ($arrCss as $css) {
                $this->_addCss($css, $css);
            }
            $arrJs = $this->_getArrayValue($headerData, 'js', array());
            foreach ($this->_getArrayValue($arrJs, 'file', array()) as $js) {
                $this->_addJs('file', $js, $js);
            }
            foreach ($this->_getArrayValue($arrJs, 'inline', array()) as $js) {
                $this->_addJs('inline', $js, $js);
            }

            if ($template) {

                $templateFile = '@front/'.$template;
                $data = array();
                $data['menus'] = $menus;
                $data['settings'] = $siteSettings;

                $header = $this->renderView($templateFile, array('data' => $data));
            }
            break;
        }

        $regions['header'] = $header;

        // add footer
        $filePath = $pathToTheme.'/config/footer.yml';
        $arrInfo = $this->_getYamlValueFromFile($filePath, array());
        $options = $this->_getArrayValue($arrInfo, 'options', array());
        foreach ($options as $value) {
            $configFile = $pathToTheme.'/config/'.$value;
            $objectData = $this->_getYamlValueFromFile($configFile);
            $template = $this->_getArrayValue($objectData, 'template');
            $arrCss = $this->_getArrayValue($objectData, 'css', array());
            foreach ($arrCss as $css) {
                $this->_addCss($css, $css);
            }
            $arrJs = $this->_getArrayValue($objectData, 'js', array());
            foreach ($this->_getArrayValue($arrJs, 'file', array()) as $js) {
                $this->_addJs('file', $js, $js);
            }
            foreach ($this->_getArrayValue($arrJs, 'inline', array()) as $js) {
                $this->_addJs('inline', $js, $js);
            }
            $data = array();
            $data['menus'] = $menus;
            $data['settings'] = $siteSettings;
            if ($template) {
                $templateFile = '@front/'.$template;
                $footer = $this->renderView($templateFile, array('data' => $data));
            }
            break;
        }
        $regions['footer'] = $footer;

        //add custom css & js for each pages
        $filePath = $pathToTheme.'/config/layout.yml';
        $arrInfo = $this->_getYamlValueFromFile($filePath, array());
        $routerLayoutData = $this->_getArrayValue($arrInfo, $type, array());
        $arrCss = $this->_getArrayValue($routerLayoutData, 'css', array());
        foreach ($arrCss as $css) {
            $this->_addCss($css, $css);
        }
        $arrJs = $this->_getArrayValue($routerLayoutData, 'js', array());
        foreach ($this->_getArrayValue($arrJs, 'file', array()) as $js) {
            $this->_addJs('file', $js, $js);
        }
        foreach ($this->_getArrayValue($arrJs, 'inline', array()) as $js) {
            $this->_addJs('inline', $js, $js);
        }

        return $regions;
    }
}