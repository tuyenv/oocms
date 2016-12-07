<?php

namespace CoreBundle\Action\AdminMenu;


use CoreBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminMenuLinkAction
{
    public static function GET(CoreAdminController $_this, Request $request)
    {
        $_this->_setPageTitle('Admin - Menu - Link');
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        //access denied
        if (!$_this->isGranted('ROLE_SUPER_ADMIN')) {
            return $_this->_error403AdminAction();
        }

        $data = new \stdClass();

        return $_this->render(
            '@admin/admin_menu/admin_menu_link_page.html.twig',
            array(
                'data' => $data,
            )
        );
    }
}