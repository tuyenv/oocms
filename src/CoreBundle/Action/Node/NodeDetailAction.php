<?php
namespace CoreBundle\Action\Node;

use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class NodeDetailAction
{
    public static function GET(CoreFrontController $_this, Request $request, $nodeId)
    {

        $detailEntity = $_this->_getEntityByID('CoreBundle:Node', $nodeId);
        if(!$detailEntity) {
            return $_this->_error403Action($request);
        }

        $nodeTitle = $detailEntity->getTitle();
        $nodeSummary = $detailEntity->getSummary();
        $nodeBody = $detailEntity->getBody();


        $_this->_setPageTitle($nodeTitle);
        $_this->_setMetaTags(
          array(
            'title' => $nodeTitle,
            'description' => $nodeSummary,
          )
        );

        $data = array();
        $data['title'] = $nodeTitle;
        $data['summary'] = $nodeSummary;
        $data['body'] = $nodeBody;

        return $_this->render(
          '@front/node/node_detail_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}