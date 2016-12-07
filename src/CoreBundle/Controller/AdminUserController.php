<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminUserController extends CoreAdminController
{
    /**
     * @Route("/admin/user", name="admin_user_page")
     */
    public function adminUserAction(Request $request)
    {
        return \CoreBundle\Action\AdminUser\AdminUserAction::GET($this, $request);
    }

    /**
     * @Route("/admin/user/{userId}/edit", name="admin_user_edit_page")
     */
    public function adminUserEditAction(Request $request, $userId)
    {
        return \CoreBundle\Action\AdminUser\AdminUserEditAction::GET($this, $request);
    }

}
