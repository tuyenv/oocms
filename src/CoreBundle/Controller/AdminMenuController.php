<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMenuController extends CoreAdminController
{
    /**
     * @Route("/admin/menu", name="admin_menu_page")
     */
    public function adminMenuAction(Request $request)
    {
        return \CoreBundle\Action\AdminMenu\AdminMenuAction::GET($this, $request);
    }

    /**
     * @Route("/admin/menu/{menuId}", name="admin_menu_detail_page")
     */
    public function adminMenuDetailAction(Request $request, $menuId)
    {
        return \CoreBundle\Action\AdminMenu\AdminMenuDetailAction::GET($this, $request, $menuId);
    }

    /**
     * @Route("/admin/menu/{menuId}/edit", name="admin_menu_edit_page")
     */
    public function adminMenuEditAction(Request $request, $menuId)
    {
        return \CoreBundle\Action\AdminMenu\AdminMenuEditAction::GET($this, $request, $menuId);
    }

    /**
     * @Route("/admin/menu/{menuId}/link", name="admin_menu_link_page")
     */
    public function adminMenuLinkAction(Request $request, $menuId)
    {
        return \CoreBundle\Action\AdminMenu\AdminMenuLinkAction::GET($this, $request, $menuId);
    }

    /**
     * @Route("/admin/menu/{menuId}/link/{linkId}/edit", name="admin_menu_link_edit_page")
     */
    public function adminMenuLinkEditAction(Request $request, $menuId, $linkId)
    {
        return \CoreBundle\Action\AdminMenu\AdminMenuLinkEditAction::GET($this, $request, $menuId, $linkId);
    }

}
