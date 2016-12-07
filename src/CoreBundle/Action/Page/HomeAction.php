<?php
namespace CoreBundle\Action\Page;

use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class HomeAction
{
    public static function GET(CoreFrontController $_this, Request $request)
    {
        $siteHomePage = $_this->_variableGet('SITE_HOME_PAGE');
        if ($siteHomePage) {
            try {
                $router = $_this->get('router');
                $arr = $router->match($siteHomePage);

                $_route = $arr['_route'];
                $_controller = $arr['_controller'];

                $arrParams = array();
                foreach ($arr as $key => $value) {
                    if ($key != '_controller' && $key != '_route') {
                        $arrParams[$key] = $value;
                    }
                }

                return $_this->forward($_controller, $arrParams, $request->query->all());
            } catch (\Exception $e) {
            }
        }

        $_this->_setPageTitle('Home page');
        $_this->_setMetaTags(
          array(
            'title' => 'Home page',
            'description' => '...',
          )
        );

        $data = array();
        $data['description'] = 'ahihi';

        return $_this->render(
          '@front/page/home_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}