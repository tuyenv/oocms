<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreFrontController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends CoreFrontController
{
    /**
     * @Route("/", name="index_page")
     */
    public function indexAction(Request $request)
    {
        return \CoreBundle\Action\Page\IndexAction::GET($this, $request);
    }
}
