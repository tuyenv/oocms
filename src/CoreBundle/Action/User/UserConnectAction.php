<?php
namespace CoreBundle\Action\User;


use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class UserConnectAction
{
    public static function GET(CoreFrontController $_this, Request $request)
    {


        $data = array();

        return $_this->render(
          '@front/user/user_connect_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}