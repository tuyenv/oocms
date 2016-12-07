<?php

namespace CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use CoreBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends CoreFrontController
{
    /**
     * @Route("/user", name="user_page")
     */
    public function userAction(Request $request)
    {
        return \CoreBundle\Action\User\UserAction::GET($this, $request);
    }

    /**
     * @Route("/user/connect", name="user_connect_page")
     */
    public function userConnectAction(Request $request)
    {
        return \CoreBundle\Action\User\UserConnectAction::GET($this, $request);
    }

    /**
     * @Route("/user/login", name="user_login_page")
     * @Method({"GET", "POST"})
     */
    public function userLoginAction(Request $request)
    {
        return \CoreBundle\Action\User\UserLoginAction::GET($this, $request);
    }

    /**
     * @Route("/user/login-check", name="user_login_check_page")
     * @Method("POST")
     */
    public function userLoginCheckAction(Request $request)
    {
        return \CoreBundle\Action\User\UserLoginCheckAction::POST($this, $request);
    }

    /**
     * @Route("/user/login-fail", name="user_login_fail_page")
     */
    public function userLoginFailAction(Request $request)
    {
        return \CoreBundle\Action\User\UserLoginFailAction::POST($this, $request);
    }

    /**
     * @Route("/user/login-success", name="user_login_success_page")
     */
    public function userLoginSuccessAction(Request $request)
    {
        return \CoreBundle\Action\User\UserLoginSuccessAction::POST($this, $request);
    }

    /**
     * @Route("/user/register", name="user_register_page")
     * @Method({"GET","POST"})
     */
    public function userRegisterAction(Request $request)
    {
        return \CoreBundle\Action\User\UserRegisterAction::GET($this, $request);
    }

    /**
     * @Route("/user/forgot-password", name="user_forgot_password_page")
     */
    public function userForgotPasswordAction(Request $request)
    {
        return \CoreBundle\Action\User\UserForgotPasswordAction::GET(
          $this,
          $request
        );
    }

    /**
     * @Route("/user/{userId}", name="user_detail_page", requirements={"userId" = "\d+"})
     */
    public function userDetailAction(Request $request, $userId)
    {
        return \CoreBundle\Action\User\UserDetailAction::GET(
          $this,
          $request,
          $userId
        );
    }

    /**
     * @Route("/user/{userId}/edit", name="user_edit_page", requirements={"userId" = "\d+"})
     */
    public function userEditAction(Request $request, $userId)
    {
        return \CoreBundle\Action\User\UserEditAction::GET(
          $this,
          $request,
          $userId
        );
    }

    /**
     * @Route("/user/active", name="user_active_page")
     */
    public function userActiveAction(Request $request, $active_code)
    {
        return \CoreBundle\Action\User\UserActiveAction::GET(
          $this,
          $request
        );
    }

    /**
     * @Route("/user/active/{active_code}", name="user_active_detail_page")
     */
    public function userActiveDetailAction(Request $request, $active_code)
    {
        return \CoreBundle\Action\User\UserActiveDetailAction::GET(
          $this,
          $request,
          $active_code
        );
    }

    /**
     * @Route("/user/logout", name="user_logout_page")
     */
    public function userLogoutAction(Request $request)
    {
        print '...';
        die;
    }
}
