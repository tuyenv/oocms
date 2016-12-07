<?php
namespace CoreBundle\Action\User;


use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class UserForgotPasswordAction
{
    public static function GET(CoreFrontController $_this, Request $request)
    {
        $currentUser = $_this->getUser();
        if ($currentUser) {
            return $_this->redirectToRoute('user_page');
        }

        $data = array();

        return $_this->render(
          '@front/user/user_forgot_password_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}