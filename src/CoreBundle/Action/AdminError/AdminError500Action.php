<?php
namespace CoreBundle\Action\AdminError;

use CoreBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminError500Action
{
    public static function GET(CoreAdminController $_this, Request $request)
    {
        $_this->_setPageTitle('500');

        $data = array();
        $data['description'] = 'Error 500';

        $response = $_this->render(
          '@front/admin_error/admin_error_500_page.html.twig',
          array(
            'data' => $data,
          )
        );
        $response->setStatusCode(500);

        return $response;
    }
}