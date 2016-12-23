<?php

namespace CoreBundle;


use CoreBundle\Entity\SystemConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoreAdminController extends CoreCommonController
{

    public function _adminError403Action(Request $request)
    {
        return \CoreBundle\Action\AdminError\AdminError403Action::GET($this, $request);
    }

    public function _adminError404Action(Request $request)
    {
        return \CoreBundle\Action\AdminError\AdminError404Action::GET($this, $request);
    }

    public function _adminError500Action(Request $request, $error)
    {
        return \CoreBundle\Action\AdminError\AdminError500Action::GET($this, $request, $error);
    }

    public function render($view, array $parameters = array(), Response $response = null)
    {
        $request = $this->container->get('request');
        $routeName = $request->get('_route');
        $parameters['site_name'] = $this->_variableGet('SITE_TITLE');
        $parameters['page_title'] = $this->_getPageTitle();
        $parameters['meta_tags'] = $this->_getMetaTags();
        $link_list = array();
        $link_list[] = array(
          'title' => 'Users',
          'link' => $this->generateUrl('admin_user_page'),
          'type' => 1,
          'items' => array(),
        );
        $link_list[] = array(
          'title' => 'Menus',
          'link' => $this->generateUrl('admin_menu_page'),
          'type' => 1,
          'items' => array(),
        );
        $link_list[] = array(
          'title' => 'Admin Menus',
          'link' => $this->generateUrl('admin_admin_menu_page'),
          'type' => 1,
          'items' => array(),
        );
//        $link_list[] = array(
//          'title' => 'Contents',
//          'link' => $this->generateUrl('admin_node_page'),
//          'type' => 1,
//          'items' => array(),
//        );
        $link_list[] = array(
          'title' => 'Settings',
          'link' => $this->generateUrl('admin_setting_page'),
          'type' => 1,
          'items' => array(),
        );

        $menuList = $this->_executeDQL(
          "SELECT m.id, m.name 
        FROM CoreBundle:AdminMenu m
        WHERE m.status = 1
        ORDER BY m.weight ASC",
          array()
        );


        $parameters['admin_menu_list'] = array(
          array(
            'name' => 'Main',
            'link_list' => $link_list,
          ),
        );
        foreach ($menuList as $value) {
            $parameters['admin_menu_list'][] = array(
              'name' => $value['name'],
              'link_list' => $this->getSystemMenus($value['id'], 0, 0, 2),
            );
        }


        if ($this->container->has('templating')) {

            return $this->container->get('templating')->renderResponse($view, $parameters, $response);
        }

        if (!$this->container->has('twig')) {
            throw new \LogicException('You can not use the "render" method if the Templating Component or the Twig Bundle are not available.');
        }

        if (null === $response) {

            $response = new Response();
        }

        $response->setContent($this->container->get('twig')->render($view, $parameters));

        return $response;
    }


    private function getSystemMenus($menuId, $parentId = 0, $level = 0, $maxLevel = 3)
    {
        if ($level == $maxLevel) {
            return array();
        }
        $dql = "SELECT ml.title, ml.path, ml.friendlyPath, ml.type
            FROM CoreBundle:AdminMenuLink ml
            WHERE ml.menuId = :menuId AND ml.parentLinkId = :parendLinkId AND ml.status = 1
            ORDER BY ml.weight ASC, ml.id ASC";
        $arr = $this->_executeDQL($dql, array(':menuId' => $menuId, ':parendLinkId' => $parentId));
        $links = array();
        foreach ($arr as $value) {
            $path = $this->_getArrayValue($value, 'path', '');
            try {
                $router = $this->get('router');

                $arr = $router->match($path);
                $_route = $arr['_route'];
                $_controller = $arr['_controller'];

                $arrParams = array();
                foreach ($arr as $key0 => $value0) {
                    if ($key0 != '_controller' && $key0 != '_route') {
                        $arrParams[$key0] = $value0;
                    }
                }
                $pathUrl = $this->generateUrl($_route, $arrParams);
            } catch (\Exception $e) {
                $pathUrl = $path;
            }

            $links[] = array(
              'title' => $this->_getArrayValue($value, 'title', ''),
              'link' => $pathUrl,
              'type' => $this->_getArrayValue($value, 'type', 0),
              'items' => array(),//$this->getSystemMenus($menuId, $this->_getArrayValue($value, 'id', 0), $level++),
            );

        }

        return $links;
    }
}