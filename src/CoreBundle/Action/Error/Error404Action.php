<?php
namespace CoreBundle\Action\Error;

use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class Error404Action
{
    public static function GET(CoreFrontController $_this, Request $request)
    {
        $_this->_setPageTitle('Page is not found!');

        $data = array();
        $data['description'] = 'Page is not found';

        $response = $_this->render(
          '@front/error/error_404_page.html.twig',
          array(
            'data' => $data,
          )
        );
        $response->setStatusCode(404);

        return $response;
    }
}