<?php

namespace CoreBundle\Action\Install;

use CoreBundle\Entity\AdminUser;
use CoreBundle\CoreCommonController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstallAction
{
    public static function GET(CoreCommonController $_this, Request $request)
    {
        $data = array();

        $adminUser = $_this->_getEntityByID('CoreBundle:AdminUser', 1);
        if ($adminUser) {
            return $_this->redirectToRoute('index_page');
        }
        $installEntity = new InstallEntity();

        $installForm = $_this->createForm(
          InstallForm::class,
          $installEntity
        );

        $installForm->handleRequest($request);


        $data['install_form'] = $installForm->createView();

        if ($installForm->isValid()) {
            $email = trim(strtolower($installEntity->getEmail()));
            $password = $installEntity->getPassword();
            $adminUser = new AdminUser();
            $adminUser->setUsername($email);
            $adminUser->setEmail($email);
            $adminUser->setPassword($_this->encodeAdminPassword($email, $password));
            $adminUser->setStatus(1);
            $adminUser->setCreatedAt(time());

            if ($adminUser = $_this->_saveEntity($adminUser)) {
                return $_this->redirectToRoute('index_page');
            } else {
                return new Response('<h1>Error!</h1>');
            }
        }

        return $_this->render(
          '@system/install/install_page.html.twig',
          array(
            'data' => $data,
          )
        );

    }
}


class InstallForm extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
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
            'data_class' => 'CoreBundle\Action\Install\InstallEntity',
          )
        );
    }
}

class InstallEntity
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