<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends CoreAdminController
{
    /**
     * @Route("/admin", name="admin_page")
     */
    public function adminAction(Request $request)
    {
        return \CoreBundle\Action\Admin\AdminAction::GET($this, $request);
    }

    /**
     * @Route("/admin/login", name="admin_login_page")
     */
    public function adminLoginAction(Request $request)
    {
        return \CoreBundle\Action\Admin\AdminLoginAction::GET($this, $request);
    }

    /**
     * @Route("/admin/logout", name="admin_logout_page")
     */
    public function adminLogoutAction(Request $request)
    {
        print '...';
        die;
    }
}
