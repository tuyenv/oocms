<?php
namespace CoreBundle\Action\User;

use CoreBundle\Entity\WebsiteUser;
use CoreBundle\CoreFrontController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Yaml;

class UserRegisterAction
{
    public static function GET(CoreFrontController $_this, Request $request)
    {
        $_this->_setPageTitle('Register');

        $useAjax = $_this->getContainerParameter('ajax_user_register_page');
        $currentUser = $_this->getUser();
        if ($currentUser) {
            return $_this->redirectToRoute('user_page');
        }

        $data = array();
        $data['errors'] = array();

        $userRegisterEntity = new UserRegisterEntity();
        $userRegisterForm = $_this->createForm(
          new UserRegisterForm(),
          $userRegisterEntity,
          array('action' => $_this->generateUrl('user_register_page'), 'method' => 'POST')
        );

        $userRegisterForm->handleRequest($request);

//        $firstName = $userRegisterEntity->getFirstName();
//        $lastName = $userRegisterEntity->getLastName();
        $email = strtolower($userRegisterEntity->getEmail());
        $password = $userRegisterEntity->getPassword();

//        if ($firstName == '') {
//            $userRegisterForm->get('firstName')->addError(new FormError('First name is required!'));
//        }
//
//        if ($lastName == '') {
//            $userRegisterForm->get('lastName')->addError(new FormError('Last name is required!'));
//        }

        if ($email == '') {
            $userRegisterForm->get('email')->addError(new FormError('Email is required!'));
        }

        if ($password == '') {
            $userRegisterForm->get('password')->addError(new FormError('Password is required!'));
        }
        // has submitted
        if ($userRegisterForm->isSubmitted()) {
            if ($userRegisterForm->isValid()) {
                $tmpUser = $_this->_getEntityByConditions(
                  'CoreBundle:WebsiteUser',
                  array(
                    'email' => $email,
                  )
                );

                if ($tmpUser) {
                    $userRegisterForm->get('email')->addError(new FormError('Email is existed'));
                    $returnData['status'] = 0;
                    $returnData['message'] = 'Email is existed!';
                } else {
                    $user = new WebsiteUser();
                    $user->setUsername($email);
                    $user->setEmail($email);
                    $user->setPassword($_this->encodePassword($email, $password));
                    $user->setStatus(1);
                    $user->setCreatedAt(time());
                    $user->setActivedAt(time());

                    $user = $_this->_saveEntity($user);
                    $userId = $user->getId();

                    $returnData['status'] = 1;
                    $returnData['message'] = 'Sent active code to '.$email.'. Please active the account!';
                }
            } else {
                $returnData['status'] = 0;
                $returnData['message'] = 'Invalid params!';
            }

            if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
                $errors = Yaml::parse($userRegisterForm->getErrorsAsString());
                $returnData['errors'] = $errors;
                return new JsonResponse($returnData);
            } else {
                $errors = Yaml::parse($userRegisterForm->getErrorsAsString());
                $data['errors'] = $errors;
            }
        }


        $data['user_register_form'] = $userRegisterForm->createView();
        $data['use_ajax'] = $useAjax;

        return $_this->render(
          '@front/user/user_register_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}

class UserRegisterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
//          ->add('firstName', TextType::class, array('required' => true))
//          ->add('lastName', TextType::class, array('required' => true))
          ->add('email', EmailType::class, array('required' => true))
          ->add(
            'password',
            RepeatedType::class,
            array(
              'type' => PasswordType::class,
              'invalid_message' => 'The password fields must match.',
              'options' => array('attr' => array()),
              'required' => true,
              'first_options' => array('label' => 'Password'),
              'second_options' => array('label' => 'Repeat Password'),
            )
          );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
          array(
            'data_class' => 'CoreBundle\Action\User\UserRegisterEntity',
          )
        );
    }
}

class UserRegisterEntity
{
//    private $firstName;
//
//    public function setFirstName($firstName)
//    {
//        $this->firstName = $firstName;
//
//        return $this;
//    }
//
//    public function getFirstName()
//    {
//        return $this->firstName;
//    }
//
//    private $lastName;
//
//    public function setLastName($lastName)
//    {
//        $this->lastName = $lastName;
//
//        return $this;
//    }
//
//    public function getLastName()
//    {
//        return $this->lastName;
//    }

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

    private $password;

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }
}