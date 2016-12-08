<?php
namespace CoreBundle\Action\User;


use CoreBundle\CoreFrontController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Yaml;

class UserForgotPasswordAction
{
    public static function GET(CoreFrontController $_this, Request $request)
    {
        $_this->_setPageTitle('Forgot Password');

        $useAjax = $_this->getContainerParameter('ajax_user_forgot_password_page');
        $currentUser = $_this->getUser();
        if ($currentUser) {
            return $_this->redirectToRoute('user_page');
        }

        $data = array();
        $data['errors'] = array();

        $entity = new UserForgotPasswordEntity();
        $form = $_this->createForm(
          new UserForgotPasswordForm(),
          $entity,
          array('action' => $_this->generateUrl('user_forgot_password_page'), 'method' => 'POST')
        );

        $form->handleRequest($request);
        $email = strtolower($entity->getEmail());

        if ($email == '') {
            $form->get('email')->addError(new FormError('Email is required!'));
        }

        // has submitted
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $tmpUser = $_this->_getEntityByConditions(
                  'CoreBundle:User',
                  array(
                    'email' => $email,
                  )
                );

                if (!$tmpUser) {
                    $form->get('email')->addError(new FormError('Email is NOT existed'));
                    $returnData['status'] = 0;
                    $returnData['message'] = 'Email is NOT existed!';
                } else {

                    $returnData['status'] = 1;
                    $returnData['message'] = 'Sent reset code to '.$email.'. Please check the email!';
                }
            } else {
                $returnData['status'] = 0;
                $returnData['message'] = 'Invalid params!';
            }

            if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
                $errors = Yaml::parse($form->getErrorsAsString());
                $returnData['errors'] = $errors;
                return new JsonResponse($returnData);
            } else {
                $errors = Yaml::parse($form->getErrorsAsString());
                $data['errors'] = $errors;
            }
        }


        $data['user_forgot_password_form'] = $form->createView();
        $data['use_ajax'] = $useAjax;

        return $_this->render(
          '@front/user/user_forgot_password_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}

class UserForgotPasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
          ->add('email', EmailType::class, array('required' => true))
          ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
          array(
            'data_class' => 'CoreBundle\Action\User\UserForgotPasswordEntity',
          )
        );
    }
}

class UserForgotPasswordEntity
{
    private $email;

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }
}