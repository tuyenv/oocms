<?php

namespace CoreBundle\Action\AdminManager;


use CoreBundle\CoreAdminController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminManagerEditAction
{
    public static function GET(
      CoreAdminController $_this,
      Request $request,
      $mangerId
    ) {
        $data = array();
        $entity = new AdminManagerEditEntity();


        $selectedUser = $_this->_getEntityByID('CoreBundle:AdminUser', $mangerId);
        if ($selectedUser) {
            //$entity->setEmail($selectedUser->getEmail());
            $entity->setUsername($selectedUser->getUsername());
            $entity->setStatus($selectedUser->getStatus());
        }

        $form = $_this->createForm(
          new AdminManagerEditForm(),
          $entity
        );

        $form->handleRequest($request);


        $data['admin_manager_edit_form'] = $form->createView();

        if ($form->isValid()) {
            $selectedUser = $_this->_getEntityByID('CoreBundle:AdminUser', $mangerId);
            if ($selectedUser) {
                $newPassword = $entity->getPassword();
                $email = $entity->getEmail();
                $status = $entity->getStatus();

                if ($newPassword) {
                    $selectedUser->setPassword($_this->encodeAdminPassword($email, $newPassword));
                    $_this->addFlash('success', 'Updated password! User Id = '.$selectedUser->getId());
                } else {
                    $_this->addFlash('info', 'No update password!');
                }

                $selectedUser->setStatus($status);
                $selectedUser = $_this->_saveEntity($selectedUser);

                return $_this->redirectToRoute('admin_manager_page', array());
            }
        }


        return $_this->render(
          '@admin/admin_manager/admin_manager_edit_page.html.twig',
          array(
            'page_title' => 'Admin - Manager - Edit',
            'data' => $data,
          )
        );
    }
}

class AdminManagerEditForm extends AbstractType
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
                'readonly' => true,
              ),
            )
          )
          /*->add(
              'email',
              EmailType::class,
              array(
                  'required' => true,
                  'attr' => array(
                      'readonly' => true,
                  ),
              )
          )*/
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
          )
          ->add(
            'status',
            ChoiceType::class,
            array(
              'choices' => array(
                '1' => 'Publish',
                '0' => 'Unpublish',
              ),
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
            'data_class' => 'CoreBundle\Action\AdminManager\AdminManagerEditEntity',
          )
        );
    }
}

class AdminManagerEditEntity
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

    private $status;

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

}