<?php
namespace CoreBundle\Action\Error;

use CoreBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminError403Action
{
    public static function GET(CoreAdminController $_this, Request $request)
    {
        $data = array();
        return $_this->render(
          '@admin/admin/admin_error_403_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}