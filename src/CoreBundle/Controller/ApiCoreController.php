<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreCommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiCoreController extends CoreCommonController
{
    /**
     * @Route("/api/user/{group}/{action}", name="api_user_page")
     */
    public function apiUserAction(Request $request, $group, $action)
    {
        return \CoreBundle\Action\ApiCore\ApiUserAction::Execute($this, $request, $group, $action);
    }
}
