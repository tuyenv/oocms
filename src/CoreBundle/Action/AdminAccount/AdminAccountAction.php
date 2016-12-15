<?php

namespace CoreBundle\Action\AdminAccount;


use CoreBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminAccountAction
{
    public static function GET(CoreAdminController $_this, Request $request)
    {
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        //access denied
        if (!$_this->isGranted('ROLE_SUPER_ADMIN')) {
            return $_this->_adminError403Action();
        }

        $data = array();

        return $_this->render(
            '@admin/admin_account/admin_account_page.html.twig',
            array(
                'data' => $data,
            )
        );
    }
}