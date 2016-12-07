<?php
namespace CoreBundle\Action\User;

use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserLoginAction
{
    public static function GET(CoreFrontController $_this, Request $request)
    {
        $_this->_setPageTitle('Login');

        $useAjax = $_this->getContainerParameter('ajax_user_login_page');

        $currentUser = $_this->getUser();

        if ($currentUser) {
            $redirectAfterLogin = $_this->getContainerParameter('redirect_after_login');
            try {
                $router = $_this->get('router');

                $arr = $router->match($redirectAfterLogin);
                $_route = $arr['_route'];
                $_controller = $arr['_controller'];

                $arrParams = array();
                foreach ($arr as $key0 => $value0) {
                    if ($key0 != '_controller' && $key0 != '_route') {
                        $arrParams[$key0] = $value0;
                    }
                }
                $pathUrl = $_this->generateUrl($_route, $arrParams);
            } catch (\Exception $e) {
                $pathUrl = $redirectAfterLogin;
            }

            return $_this->redirect($pathUrl);
        }

        $data = array();
        $authenticationUtils = $_this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $data['use_ajax'] = $useAjax;

        return $_this->render(
          '@front/user/user_login_page.html.twig',
          array(
            'data' => $data,
            'page_title' => 'Login',
            'last_username' => $lastUsername,
            'error' => $error,
          )
        );
    }
}