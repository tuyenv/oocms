<?php

namespace CoreBundle\Action\AdminMenu;


use CoreBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminMenuAction
{
    public static function GET(CoreAdminController $_this, Request $request)
    {
        $_this->_setPageTitle('Admin - Menu');
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        //access denied
        if (!$_this->isGranted('ROLE_SUPER_ADMIN')) {
            return $_this->_adminError403Action();
        }

        $perPage = 10;
        $perPage = intval($request->query->get('length', $perPage));
        $currentPage = intval($request->query->get('page', 1));
        $dql = 'SELECT u.id, u.code, u.name, u.status
        FROM CoreBundle:Menu u
        ORDER BY u.createdAt DESC';
        $result = $_this->_executePagerDQL(
          $dql,
          array(),
          $perPage,
          $currentPage
        );


        $dql = 'SELECT COUNT(u.id) AS total
        FROM CoreBundle:Menu u
        ORDER BY u.createdAt DESC';
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
            $tmp['name'] = $value['name'];
            $tmp['code'] = $value['code'];
            $tmp['status'] = $value['status'];
            $action_links = array();
            $action_links[] = array(
              'title' => 'Edit',
              'link' => $_this->generateUrl('admin_menu_edit_page', array('menuId' => $value['id'])),
            );
            $action_links[] = array(
              'title' => 'Add New Link',
              'link' => $_this->generateUrl('admin_menu_link_edit_page', array('menuId' => $value['id'], 'linkId' => 0)),
            );

            $dql02 = "SELECT ml.id, ml.title, ml.path, ml.friendlyPath, ml.type
            FROM CoreBundle:MenuLink ml
            WHERE ml.menuId = :menuId AND ml.parentLinkId = 0
            ORDER BY ml.weight ASC, ml.id ASC";
            $arr02 = $_this->_executeDQL(
              $dql02,
              array(
                'menuId' => $value['id'],
              )
            );
            $menuList = array();
            foreach ($arr02 as $value02) {
                $tmp02 = array();
                $tmp02['title'] = $value02['title'];
                $tmp02['link'] = $_this->_getSymfonyUrl($value02['path']);
                $tmp02['edit_link'] = $_this->generateUrl(
                  'admin_menu_link_edit_page',
                  array(
                    'menuId' => $value['id'],
                    'linkId' => $value02['id'],
                  )
                );
                $dql03 = "SELECT ml.id, ml.title, ml.path, ml.friendlyPath, ml.type
                FROM CoreBundle:MenuLink ml
                WHERE ml.menuId = :menuId AND ml.parentLinkId = :parentLinkId
                ORDER BY ml.weight ASC, ml.id ASC";
                $arr03 = $_this->_executeDQL(
                  $dql03,
                  array(
                    'menuId' => $value['id'],
                    'parentLinkId' => $value02['id'],
                  )
                );

                $subMenuList = array();
                foreach ($arr03 as $value03) {
                    $tmp03 = array();
                    $tmp03['title'] = $value03['title'];
                    $tmp03['link'] = $_this->_getSymfonyUrl($value03['path']);
                    $tmp03['edit_link'] = $_this->generateUrl(
                      'admin_menu_link_edit_page',
                      array(
                        'menuId' => $value['id'],
                        'linkId' => $value03['id'],
                      )
                    );
                }
                $tmp02['sub_menu_list'] = $subMenuList;
                $menuList[] = $tmp02;
            }

            $tmp['menu_list'] = $menuList;
            $tmp['action_link_list'] = $action_links;
            $items[] = $tmp;

        }

        $queries = array();
        $pager = $_this->_getPager(
          $currentPage,
          ceil($total / $perPage),
          'admin_menu_page',
          $queries
        );

        $data = array(
          'currentPage' => $currentPage,
          'totalPages' => ceil($total / $perPage),
          'pager' => $pager,
          'items' => $items,
        );

        return $_this->render(
          '@admin/admin_menu/admin_menu_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}