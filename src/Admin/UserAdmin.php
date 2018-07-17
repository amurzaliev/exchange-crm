<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('username', TextType::class);
        $formMapper->add('email', EmailType::class);
        $formMapper->add('password', PasswordType::class);
        $formMapper->add('plainPassword', PasswordType::class);
        $formMapper->add('roles', ChoiceType::class,[
            'choices'=>[
                'User'=>'ROLE_USER',
                'Admin'=>'ROLE_ADMIN'
            ],
            'expanded' => false,
            'multiple' => true,
            'required' => false
        ]);
        $formMapper->add('enabled', CheckboxType::class, [
            'required' => null
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('username');
        $datagridMapper->add('email');


    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('username');
        $listMapper->addIdentifier('email');
        $listMapper->addIdentifier('enabled');
        $listMapper->addIdentifier('roles');
        $listMapper->add('_action',null,array(
            'actions' => array(
                'show'=> array(),
                'edit'=> array(),
                'delete'=> array()
            )
        ));
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('username');
        $show->add('email');
        $show->add('roles');
        $show->add('enabled');

    }

}
