<?php
namespace CoreBundle\Action\User;

use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserLoginFailAction
{
    public static function POST(CoreFrontController $_this, Request $request)
    {
        $useAjax = $_this->getContainerParameter('ajax_user_login_page');
        if ($useAjax) {
            $returnData = array();
            $returnData['status'] = 0;
            $returnData['message'] = 'Fail';

            return new JsonResponse($returnData);
        } else {
            return $_this->redirectToRoute('user_login_page', array('type' => 'error'));
        }

    }
}