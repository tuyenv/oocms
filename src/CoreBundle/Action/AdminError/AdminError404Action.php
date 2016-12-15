<?php
namespace CoreBundle\Action\AdminError;

use CoreBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminError404Action
{
    public static function GET(CoreAdminController $_this, Request $request)
    {
        $_this->_setPageTitle('Page is not found!');

        $data = array();
        $data['description'] = 'Page is not found';

        $response = $_this->render(
          '@front/admin_error/admin_error_404_page.html.twig',
          array(
            'data' => $data,
          )
        );
        $response->setStatusCode(404);

        return $response;
    }
}