<?php

namespace App\Form;

use App\Entity\PermissionGroup;
use App\Entity\Staff;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', TextType::class, ['label' => 'Должность'])
            ->add('permissionGroup', EntityType::class, [
                'label' => 'Группа',
                'class' => PermissionGroup::class,
                'choice_label' => 'title',
                'required' => false,
                'placeholder' => 'Выберите группу'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Staff::class,
        ]);
    }
}
