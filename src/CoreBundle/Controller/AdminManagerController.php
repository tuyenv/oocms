<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminManagerController extends CoreAdminController
{
    /**
     * @Route("/admin/manager", name="admin_manager_page")
     */
    public function adminManagerAction(Request $request)
    {
        return \CoreBundle\Action\AdminManager\AdminManagerAction::GET($this, $request);
    }

    /**
     * @Route("/admin/manager/{managerId}", name="admin_manager_defail_page", requirements={"managerId": "\d+"})
     */
    public function adminAccountDetailAction(Request $request, $managerId)
    {
        return \CoreBundle\Action\AdminManager\AdminManagerDetailAction::GET($this, $request, $managerId);
    }

    /**
     * @Route("/admin/manager/{managerId}/edit", name="admin_manager_edit_page", requirements={"managerId": "\d+"})
     */
    public function adminManagerEditAction(Request $request, $managerId)
    {
        return \CoreBundle\Action\AdminManager\AdminManagerEditAction::GET($this, $request, $managerId);
    }

    /**
     * @Route("/admin/manager/{managerId}/change-password", name="admin_manager_change_password_page", requirements={"managerId": "\d+"})
     */
    public function adminManagerChangePasswordAction(Request $request, $managerId)
    {
        return \CoreBundle\Action\AdminManager\AdminManagerChangePasswordAction::GET($this, $request, $managerId);
    }

    /**
     * @Route("/admin/manager/new", name="admin_manager_new_page")
     */
    public function adminManagerNewAction(Request $request)
    {
        return \CoreBundle\Action\AdminManager\AdminManagerNewAction::GET($this, $request);
    }

    /**
     * @Route("/admin/manager/{managerId}/delete", name="admin_manager_delete_page", requirements={"managerId": "\d+"})
     */
    public function adminManagerDeleteAction(Request $request, $managerId)
    {
        return \CoreBundle\Action\AdminManager\AdminManagerDeleteAction::GET($this, $request, $managerId);
    }
}
