<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreFrontController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NodeController extends CoreFrontController
{
    /**
     * @Route("/node/{nodeId}", name="node_detail_page")
     */
    public function nodeDetailAction(Request $request, $nodeId)
    {
        return \CoreBundle\Action\Node\NodeDetailAction::GET($this, $request, $nodeId);
    }
}
