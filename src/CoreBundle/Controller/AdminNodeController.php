<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminNodeController extends CoreAdminController
{
    /**
     * @Route("/admin/node", name="admin_node_page")
     */
    public function adminNodeAction(Request $request)
    {
        return \CoreBundle\Action\AdminNode\AdminNodeAction::GET($this, $request);
    }

    /**
     * @Route("/admin/node/{nodeId}/edit", name="admin_node_edit_page")
     */
    public function adminNodeEditAction(Request $request, $nodeId)
    {
        return \CoreBundle\Action\AdminNode\AdminNodeEditAction::GET($this, $request, $nodeId);
    }

}
