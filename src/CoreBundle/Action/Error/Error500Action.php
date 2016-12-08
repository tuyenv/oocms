<?php
namespace CoreBundle\Action\Error;

use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class Error500Action
{
    public static function GET(CoreFrontController $_this, Request $request)
    {
        $_this->_setPageTitle('500');

        $data = array();
        $data['description'] = 'Error 500';

        $response = $_this->render(
          '@front/error/error_500_page.html.twig',
          array(
            'data' => $data,
          )
        );
        $response->setStatusCode(500);

        return $response;
    }
}