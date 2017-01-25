<?php
namespace CoreBundle\Action\ApiCore;

use CoreBundle\CoreCommonController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiUserAction
{
    public static function Execute(CoreCommonController $_this, Request $request, $group, $action)
    {
        $method = $request->getMethod();

        switch ($method) {
            case 'GET':
                $returnData = self::_GET($_this, $request, $group, $action);
                break;
            case 'POST':
                $returnData = self::_POST($_this, $request, $group, $action);
                break;
            default:
                $returnData = array();
                $returnData['status'] = 0;
                $returnData['message'] = 'Method is invalid!';
                break;
        }

        return new JsonResponse($returnData);
    }

    private function _GET(CoreCommonController $_this, Request $request, $group, $action)
    {
        switch ($group) {
            case 'social':
                switch ($action) {
                    case 'sidebar':
                        $returnData = ApiBb2016Common::Sidebar($_this, $request);
                        break;

                        break;
                    default:
                        $returnData = array('status' => 0, 'message' => 'Not found');
                        break;
                }
                break;
            default:
                $returnData = array('status' => 0, 'message' => 'Not found');
                break;
        }

        return $returnData;
    }

    private function _POST(CoreCommonController $_this, Request $request, $group, $action)
    {
        switch ($group) {
            case 'social':
                switch ($action) {
                    case 'register':
                        $returnData = \CoreBundle\Action\ApiCore\ApiUser\POST\ApiUserSocialRegister::POST($_this, $request);
                        break;

                        break;
                    default:
                        $returnData = array('status' => 0, 'message' => 'Not found');
                        break;
                }
                break;
            default:
                $returnData = array('status' => 0, 'message' => 'Not found');
                break;
        }

        return $returnData;
    }
}