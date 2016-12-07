<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccountController extends CoreAdminController
{
    /**
     * @Route("/admin/account", name="admin_account_page")
     */
    public function adminAccountAction(Request $request)
    {
        return \CoreBundle\Action\AdminAccount\AdminAccountAction::GET($this, $request);
    }

    /**
     * @Route("/admin/account/{accountId}", name="admin_account_defail_page")
     */
    public function adminAccountDetailAction(Request $request, $accountId)
    {
        return \CoreBundle\Action\AdminAccount\AdminAccountDetailAction::GET($this, $request, $accountId);
    }

    /**
     * @Route("/admin/account/{accountId}/edit", name="admin_account_edit_page")
     */
    public function adminAccountEditAction(Request $request, $accountId)
    {
        return \CoreBundle\Action\AdminAccount\AdminAccountEditAction::GET($this, $request, $accountId);
    }

}
