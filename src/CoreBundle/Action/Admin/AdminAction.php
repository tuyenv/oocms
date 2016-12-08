<?php

namespace CoreBundle\Action\Admin;


use CoreBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminAction
{
    public static function GET(CoreAdminController $_this, Request $request)
    {
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        //access denied
        if (!$_this->isGranted('ROLE_SUPER_ADMIN')) {
            return $_this->_error403AdminAction();
        }

        $_this->_setPageTitle('Admin - Dashboard');

        $data = array();

        return $_this->render(
            '@admin/admin/admin_page.html.twig',
            array(
                'data' => $data,
            )
        );
    }
}