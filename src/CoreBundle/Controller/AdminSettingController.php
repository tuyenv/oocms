<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminSettingController extends CoreAdminController
{
    /**
     * @Route("/admin/setting", name="admin_setting_page")
     */
    public function adminSettingAction(Request $request)
    {
        return \CoreBundle\Action\AdminSetting\AdminSettingAction::GET($this, $request);
    }

    /**
     * @Route("/admin/setting/{settingId}/edit", name="admin_setting_edit_page")
     */
    public function adminSettingEditAction(Request $request, $settingId)
    {
        return \CoreBundle\Action\AdminSetting\AdminSettingEditAction::GET($this, $request, $settingId);
    }

}
