<?php
namespace CoreBundle\Action\Error;

use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class Error403Action
{
    public static function GET(CoreFrontController $_this, Request $request)
    {
        $_this->_setPageTitle('Access Denied!');

        $data = array();
        $data['description'] = 'Access Denied';

        $response = $_this->render(
          '@front/error/error_403_page.html.twig',
          array(
            'data' => $data,
          )
        );
        $response->setStatusCode(403);

        return $response;
    }
}