<?php

namespace App\Form;

use App\Entity\PermissionGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermissionGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('alias')
            ->add('create_personal',CheckboxType::class, [
                'required' => false,
                'label' => 'Добавления персонала'
            ])
            ->add('edit_personal',CheckboxType::class, [
                'required' => false,
                'label' => 'Редактирования персонала'
            ])
            ->add('view_personal',CheckboxType::class, [
                'required' => false,
                'label' => 'Просмотр персонала'
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PermissionGroup::class,
        ]);
    }
}
