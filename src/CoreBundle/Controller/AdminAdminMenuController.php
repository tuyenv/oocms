<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAdminMenuController extends CoreAdminController
{
    /**
     * @Route("/admin/admin-menu", name="admin_admin_menu_page")
     */
    public function adminAdminMenuAction(Request $request)
    {
        return \CoreBundle\Action\AdminAdminMenu\AdminAdminMenuAction::GET($this, $request);
    }

    /**
     * @Route("/admin/admin-menu/{menuId}/edit", name="admin_admin_menu_edit_page")
     */
    public function adminAdminMenuEditAction(Request $request, $menuId)
    {
        return \CoreBundle\Action\AdminAdminMenu\AdminAdminMenuEditAction::GET($this, $request, $menuId);
    }

    /**
     * @Route("/admin/admin-menu/{menuId}/link/{linkId}/edit", name="admin_admin_menu_link_edit_page")
     */
    public function adminAdminMenuLinkEditAction(Request $request, $menuId, $linkId)
    {
        return \CoreBundle\Action\AdminAdminMenu\AdminAdminMenuLinkEditAction::GET($this, $request, $menuId, $linkId);
    }

}
