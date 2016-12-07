<?php
namespace CoreBundle\Action\User;


use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class UserAction
{
    public static function GET(CoreFrontController $_this, Request $request)
    {
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('user_login_page');
        }
        // get User
        $currentUser = $_this->getUser();
        $currentUserId = $currentUser->getId();

        return $_this->redirectToRoute('user_detail_page', array('userId' => $currentUserId));
    }
}