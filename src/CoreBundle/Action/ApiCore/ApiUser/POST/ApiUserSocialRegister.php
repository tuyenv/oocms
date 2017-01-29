<?php

namespace CoreBundle\Action\ApiCore\ApiUser\POST;


use CoreBundle\CoreCommonController;
use CoreBundle\Entity\WebsiteUser;
use Symfony\Component\HttpFoundation\Request;

class ApiUserSocialRegister
{

    public static function POST(CoreCommonController $_this, Request $request)
    {
        $returnData = array();


        $redirectUrl = $_this->generateUrl('index_page');

        $userString = $request->request->get('user', '');
        if ($_this->_isJSON($userString)) {
            $userData = json_decode($userString);

            $displayName = $_this->_getObjectValue($userData, 'displayName');
            $email = $_this->_getObjectValue($userData, 'email');
            $email = strtolower($email);
            $emailVerified = $_this->_getObjectValue($userData, 'emailVerified');
            $photoURL = $_this->_getObjectValue($userData, 'photoURL');
            $isAnonymous = $_this->_getObjectValue($userData, 'isAnonymous');
            $uid = $_this->_getObjectValue($userData, 'uid');
            $providerData = $_this->_getObjectValue($userData, 'providerData');

            $websiteUserEntity = $_this->_getEntityByConditions('CoreBundle:WebsiteUser', array('email' => $email));
            if ($websiteUserEntity) {
                $redirectUrl = $_this->generateUrl('user_page', array());
                $returnData['status'] = 1;
                $returnData['message'] = 'Account is existed!';
            } else {
                $websiteUserEntity = new WebsiteUser();
                $websiteUserEntity->setCreatedAt(time());
                $websiteUserEntity->setActivedAt(time());
                $websiteUserEntity->setEmail($email);
                $websiteUserEntity->setStatus(0);
                $websiteUserEntity->setUsername($email);
                $websiteUserEntity->setPassword('unknown');
                $websiteUserEntity = $_this->_saveEntity($websiteUserEntity);


                $returnData['status'] = 1;
                $returnData['message'] = 'Added new account!';
            }

            $redirectUrl = $_this->generateUrl('user_page', array('_token' => md5('bbb')));
        } else {

        }



        $data = array();
        $data['redirect_url'] = $redirectUrl;
        $returnData['data'] = $data;

        return $returnData;
    }

}