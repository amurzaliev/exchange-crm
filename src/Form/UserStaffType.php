<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class UserStaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', UserType::class, [
                'label' => false
            ])
            ->add('staff',  StaffType::class, [
                'label' => false
            ])
            ->add('save', SubmitType::class)
        ;
    }
}
