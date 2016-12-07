<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreCommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class InstallController extends CoreCommonController
{
    /**
     * @Route("/install", name="install_page")
     */
    public function installAction(Request $request)
    {
        return \CoreBundle\Action\Install\InstallAction::GET($this, $request);
    }

}
