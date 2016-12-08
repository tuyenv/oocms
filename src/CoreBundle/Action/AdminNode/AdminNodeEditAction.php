<?php

namespace CoreBundle\Action\AdminNode;


use CoreBundle\CoreAdminController;
use CoreBundle\Entity\Node;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminNodeEditAction
{
    public static function GET(CoreAdminController $_this, Request $request, $nodeId)
    {
        $userEntity = $_this->getUser();

        $_this->_setPageTitle('Admin - Content Edit');
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        //access denied
        if (!$_this->isGranted('ROLE_SUPER_ADMIN')) {
            return $_this->_error403AdminAction();
        }

        if ($userEntity) {
            $userId = $userEntity->getId();
        } else {
            $userId = 0;
        }

        $data = array();
        if ($entity = $_this->_getEntityByID('CoreBundle:Node', $nodeId)) {

        } else {
            $entity = new Node();
            $entity->setCreatedBy($userId);
            $entity->setCreatedAt(time());
            $entity->setUpdatedAt(time());
            $entity->setStatus(1);
        }

        $form = $_this->createForm(
          AdminNodeEditForm::class,
          $entity
        );

        $form->handleRequest($request);


        if ($form->isValid()) {
            $entity->setUpdatedAt(time());
            $entity->setUpdatedBy($userId);
            $entity = $_this->_saveEntity($entity);

            //$adminSettingForm->get('email')->addError(new FormError('Email is exist!'));
            $_this->addFlash('success', 'Updated! Id = '.$entity->getId());

            return $_this->redirectToRoute('admin_node_page');
        }
        $data['admin_node_edit_form'] = $form->createView();

        return $_this->render(
          '@admin/admin_node/admin_node_edit_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}


class AdminNodeEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('title', TextType::class, array('required' => true))
          ->add('summary', TextareaType::class, array('required' => true))
          ->add('body', TextareaType::class, array('required' => true))
          ->add(
            'status',
            ChoiceType::class,
            array(
              'choices' => array(
                1 => 'Publish',
                0 => 'Unpublish',
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
            'data_class' => 'CoreBundle\Entity\Node',
          )
        );
    }
}