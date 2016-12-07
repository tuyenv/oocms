<?php

namespace CoreBundle\Utility;

use CoreBundle\CoreCommonController;
use CoreBundle\Entity\AdminUser;
use CoreBundle\Entity\User;

class SitePassword
{
    public static function encodePassword(CoreCommonController $_this, $email, $password)
    {
        $user = new User();
        $user->setUsername($email);
        $user->setEmail($email);
        $plainPassword = $password;
        $encoder = $_this->getContainer('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $plainPassword);

        return $encoded;
    }

    public static function encodeAdminPassword(CoreCommonController $_this, $email, $password)
    {
        $adminUser = new AdminUser();
        $adminUser->setUsername($email);
        $adminUser->setEmail($email);
        $plainPassword = $password;
        $encoder = $_this->getContainer('security.password_encoder');
        $encoded = $encoder->encodePassword($adminUser, $plainPassword);

        return $encoded;
    }
}