<?php

namespace CoreBundle\Action\AdminManager;


use CoreBundle\CoreAdminController;
use CoreBundle\Entity\AdminUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminManagerNewAction
{
    public static function GET(
      CoreAdminController $_this,
      Request $request
    ) {
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        //access denied
        if (!$_this->isGranted('ROLE_SUPER_ADMIN')) {
            return $_this->_adminError403Action($request);
        }

        $data = array();

        $adminManagerNewEntity = new AdminManagerNewEntity();


        $adminManagerNewForm = $_this->createForm(
          AdminManagerNewForm::class,
          $adminManagerNewEntity
        );

        $adminManagerNewForm->handleRequest($request);


        $data['admin_manager_new_form'] = $adminManagerNewForm->createView();

        if ($adminManagerNewForm->isValid()) {
            $username = $adminManagerNewEntity->getUsername();
            $newPassword = $adminManagerNewEntity->getPassword();

            $selectedUser = $_this->_getEntityByConditions(
              'CoreBundle:AdminUser',
              array(
                'username' => $username,
              )
            );
            if ($selectedUser) {
                $_this->addFlash('info', 'Did nothing!');

            } else {
                $newUser = new AdminUser();
                $newUser->setUsername($username);
                $newUser->setEmail($username);
                $newUser->setPassword(
                  $_this->encodeAdminPassword($username, $newPassword)
                );
                $newUser->setStatus(1);
                $newUser->setCreatedAt(time());


                $newUser = $_this->_saveEntity($newUser);
                $_this->addFlash('success', 'Created new user! New Id = '.$newUser->getId());

                return $_this->redirectToRoute('admin_manager_page', array());

            }


        }
        $_this->_setPageTitle('Manager - Add new');

        return $_this->render(
          '@admin/admin_manager/admin_manager_new_page.html.twig',
          array(
            'page_title' => 'Admin - Add Manager',
            'data' => $data,
          )
        );
    }
}
class AdminManagerNewForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add(
            'username',
            TextType::class,
            array(
              'required' => true,
              'attr' => array(
                'readonly' => false,
              ),
            )
          )
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
            'data_class' => 'CoreBundle\Action\AdminManager\AdminManagerNewEntity',
          )
        );
    }
}

class AdminManagerNewEntity
{
    private $username;

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
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