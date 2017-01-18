<?php

namespace CoreBundle\Action\AdminManager;


use CoreBundle\CoreAdminController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminManagerChangePasswordAction
{
    public static function GET(
      CoreAdminController $_this,
      Request $request,
      $mangerId
    ) {
        $data = array();
        $entity = new AdminManagerChangePasswordEntity();


        $selectedUser = $_this->_getEntityByID('CoreBundle:AdminUser', $mangerId);
        if ($selectedUser) {
            //$entity->setEmail($selectedUser->getEmail());
            $entity->setUsername($selectedUser->getUsername());
        }

        $form = $_this->createForm(
          AdminManagerChangePasswordForm::class,
          $entity
        );

        $form->handleRequest($request);


        $data['admin_manager_change_password_form'] = $form->createView();

        if ($form->isValid()) {
            $selectedUser = $_this->_getEntityByID('CoreBundle:AdminUser', $mangerId);
            if ($selectedUser) {
                $newPassword = $entity->getPassword();
                $email = $entity->getEmail();
                if ($newPassword) {
                    $selectedUser->setPassword($_this->encodeAdminPassword($email, $newPassword));
                    $selectedUser = $_this->_saveEntity($selectedUser);
                    $_this->addFlash('success', 'Updated password! User Id = '.$selectedUser->getId());
                } else {
                    $_this->addFlash('info', 'Did nothing!');
                }

                return $_this->redirectToRoute('admin_manager_page', array());
            }


        }


        return $_this->render(
          '@admin/admin_manager/admin_manager_change_password_page.html.twig',
          array(
            'page_title' => 'Admin - Manager - Change Password',
            'data' => $data,
          )
        );
    }
}

class AdminManagerChangePasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add(
            'password',
            RepeatedType::class,
            array(
              'type' => PasswordType::class,
              'invalid_message' => 'The password fields must match.',
              'options' => array('attr' => array()),
              'required' => false,
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
            'data_class' => 'CoreBundle\Action\AdminManager\AdminManagerChangePasswordEntity',
          )
        );
    }
}

class AdminManagerChangePasswordEntity
{
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