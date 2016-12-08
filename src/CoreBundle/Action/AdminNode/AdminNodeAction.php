<?php

namespace CoreBundle\Action\AdminNode;


use CoreBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminNodeAction
{
    public static function GET(CoreAdminController $_this, Request $request)
    {
        $_this->_setPageTitle('Admin - Content');
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        //access denied
        if (!$_this->isGranted('ROLE_SUPER_ADMIN')) {
            return $_this->_error403AdminAction();
        }

        $perPage = 10;
        $perPage = intval($request->query->get('length', $perPage));
        $currentPage = intval($request->query->get('page', 1));
        $dql = 'SELECT n.id, n.title, n.status
        FROM CoreBundle:Node n
        ORDER BY n.updatedAt DESC';
        $result = $_this->_executePagerDQL(
          $dql,
          array(),
          $perPage,
          $currentPage
        );


        $dql = 'SELECT COUNT(n.id) AS total
        FROM CoreBundle:Node n';
        $result2 = $_this->_executeDQL(
          $dql,
          array()
        );

        $arr = $result;
        $total = $result2[0]['total'];
        $items = array();
        foreach ($arr as $key => $value) {
            $tmp = array();

            $tmp['id'] = $value['id'];
            $tmp['detail_link'] = '';//$_this->generateUrl()$value['id'];
            $tmp['title'] = $value['title'];
            $tmp['status'] = $value['status'];
            $action_links = array();
            $action_links[] = array(
              'title' => 'Edit',
              'link' => $_this->generateUrl('admin_node_edit_page', array('nodeId' => $value['id'])),
            );
            $tmp['action_link_list'] = $action_links;
            $items[] = $tmp;

        }

        $queries = array();
        $pager = $_this->_getPager(
          $currentPage,
          ceil($total / $perPage),
          'admin_node_page',
          $queries
        );

        $data = array(
          'currentPage' => $currentPage,
          'totalPages' => ceil($total / $perPage),
          'pager' => $pager,
          'items' => $items,
        );

        return $_this->render(
          '@admin/admin_node/admin_node_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}