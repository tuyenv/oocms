<?php
namespace CoreBundle\Action\User;


use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class UserEditAction
{
    public static function GET(CoreFrontController $_this, Request $request, $userId)
    {
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('user_login_page');
        }
        // get User
        $currentUser = $_this->getUser();
        $currentUserId = $currentUser->getId();
        // if current Id is not Edited Id
        if ($currentUserId != $userId) {
            return $_this->_error403Action($request);
        }

        $data = array();

        return $_this->render(
          '@front/user/user_edit_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}