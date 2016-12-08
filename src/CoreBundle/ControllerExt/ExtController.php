<?php

namespace CoreBundle\ControllerExt;


use CoreBundle\CoreFrontController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ExtController extends CoreFrontController
{
    /**
     * @Route("/{_locale}", name="friendly_url_1_page")
     */
    public function friendlyUrl1Action(Request $request, $_locale)
    {
        $friendlyUrl = '';

        return $this->friendlyUrlAction($request, $_locale, $friendlyUrl);
    }

    /**
     * @Route("/{_locale}/", name="friendly_url_1x_page")
     */
    public function friendlyUrl1xAction(Request $request, $_locale)
    {
        $friendlyUrl = '';

        return $this->friendlyUrlAction($request, $_locale, $friendlyUrl);
    }

    /**
     * @Route("/{_locale}/{param2}", name="friendly_url_2_page")
     */
    public function friendlyUrl2Action(Request $request, $_locale, $param2)
    {
        $friendlyUrl = '/'.$param2;

        return $this->friendlyUrlAction($request, $_locale, $friendlyUrl);
    }

    /**
     * @Route("/{_locale}/{param2}/{param3}", name="friendly_url_3_page")
     */
    public function friendlyUrl3Action(
      Request $request,
      $_locale,
      $param2,
      $param3
    ) {
        $friendlyUrl = '/'.$param2.'/'.$param3;

        return $this->friendlyUrlAction($request, $_locale, $friendlyUrl);
    }

    /**
     * @Route("/{_locale}/{param2}/{param3}/{param4}", name="friendly_url_4_page")
     */
    public function friendlyUrl4Action(
      Request $request,
      $_locale,
      $param2,
      $param3,
      $param4
    ) {
        $friendlyUrl = '/'.$param2.'/'.$param3.'/'.$param4;

        return $this->friendlyUrlAction($request, $_locale, $friendlyUrl);
    }

    /**
     * @Route("/{_locale}/{param2}/{param3}/{param4}/{param5}", name="friendly_url_5_page")
     */
    public function friendlyUrl5Action(
      Request $request,
      $_locale,
      $param2,
      $param3,
      $param4,
      $param5
    ) {
        $friendlyUrl = '/'.$param2.'/'.$param3.'/'.$param4.'/'.$param5;

        return $this->friendlyUrlAction($request, $_locale, $friendlyUrl);
    }

    private function friendlyUrlAction(Request $request, $_locale, $friendlyUrl)
    {
        $request = $this->container->get('request');
        $curLocale = $request->getLocale();
        $defaultLanguage = 'en';
        //$arrLanaguages = $this->_getAllLanguages('en');
        $arrLanguages = array(
          'en' => 'English',
          'vi' => 'Vietnamese',
        );

        if (!isset($arrLanguages[$_locale])
          && $_locale == $curLocale
          && $_locale != $defaultLanguage

        ) {
            $friendlyUrl = '/'.$_locale.$friendlyUrl;
        }

        if (!$friendlyUrl) {
            $friendlyUrl = '/';
        }
        try {
            $router = $this->get('router');
            $arr = $router->match($friendlyUrl);

            $_route = $arr['_route'];
            $_controller = $arr['_controller'];
            if ($_route == 'friendly_url_1_page'
              || $_route == 'friendly_url_2_page'
              || $_route == 'friendly_url_3_page'
              || $_route == 'friendly_url_4_page'
              || $_route == 'friendly_url_5_page'
            ) {



                $friendlyUrlEntity = $this->_getEntityByConditions('CoreBundle:FriendlyUrl', array('alias' => $friendlyUrl));

                if($friendlyUrlEntity) {
                    $friendlyUrl = $friendlyUrlEntity->getSource();
                    $router = $this->get('router');
                    $arr = $router->match($friendlyUrl);

                    $_route = $arr['_route'];
                    $_controller = $arr['_controller'];
                    if ($_route == 'friendly_url_1_page'
                      || $_route == 'friendly_url_2_page'
                      || $_route == 'friendly_url_3_page'
                      || $_route == 'friendly_url_4_page'
                      || $_route == 'friendly_url_5_page'
                    ) {
                        // do nothing
                    } else {
                        $arrParams = array();
                        foreach ($arr as $key => $value) {
                            if ($key != '_controller' && $key != '_route') {
                                $arrParams[$key] = $value;
                            }
                        }

                        return $this->forward($_controller, $arrParams, $request->query->all());
                    }

                }

                if (
                  strstr($friendlyUrl, '/_wdt/', true) !== false
                  || strstr($friendlyUrl, '/_wdt/', true) !== false
                  || strstr($friendlyUrl, '.jpg', true) !== false
                  || strstr($friendlyUrl, '.css', true) !== false
                  || strstr($friendlyUrl, '.js', true) !== false
                  || strstr($friendlyUrl, 'profile', true) !== false
                ) {
                    header("HTTP/1.0 404 Not Found");
                    print '';
                    die;
                }

                return $this->_error404Action($request);
            } else {
                $arrParams = array();
                foreach ($arr as $key => $value) {
                    if ($key != '_controller' && $key != '_route') {
                        $arrParams[$key] = $value;
                    }
                }

                return $this->forward($_controller, $arrParams, $request->query->all());
            }
        } catch (\Exception $ex) {
            return $this->_error500Action($request, $ex);
        }
    }

}