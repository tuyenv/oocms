<?php
namespace CoreBundle\Action\AdminError;

use CoreBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminError403Action
{
    public static function GET(CoreAdminController $_this, Request $request)
    {
        $_this->_setPageTitle('Access Denied!');

        $data = array();
        $data['description'] = 'Access Denied';

        $response = $_this->render(
          '@front/admin_error/admin_error_403_page.html.twig',
          array(
            'data' => $data,
          )
        );
        $response->setStatusCode(403);

        return $response;
    }
}