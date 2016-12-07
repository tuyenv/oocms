<?php
namespace CoreBundle\Action\User;

use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserLoginCheckAction
{
    public static function POST(CoreFrontController $_this, Request $request)
    {
        $data = array();
        return new JsonResponse(array('status' => 1, 'error' => ''));
    }
}