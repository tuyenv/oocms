<?php

namespace CoreBundle\Action\Admin;


use CoreBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminLoginAction
{
    public static function GET(CoreAdminController $_this, Request $request)
    {
        $currentUser = $_this->getUser();
        if ($currentUser) {
            return $_this->redirectToRoute('admin_page');
        }

        $authenticationUtils = $_this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $_this->render(
          '@admin/admin/admin_login_page.html.twig',
          array(
            'page_title' => 'Login',
            'last_username' => $lastUsername,
            'error' => $error,
          )
        );


    }
}

