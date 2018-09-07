<?php

namespace App\Form;

use App\Entity\CurrencyRate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyRateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('purchase', NumberType::class, ['label' => 'Покупка'])
            ->add('sale', NumberType::class, ['label' => 'Продажа'])
            ->add('save', SubmitType::class, ['label' => 'Добавить'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CurrencyRate::class,
        ]);
    }
}
