<?php
namespace CoreBundle\Action\User;


use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class UserDetailAction
{
    public static function GET(CoreFrontController $_this, Request $request, $userId)
    {
        $data = array();

        return $_this->render(
          '@front/user/user_detail_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}