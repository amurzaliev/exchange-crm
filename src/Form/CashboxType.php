<?php

namespace App\Form;

use App\Entity\Cashbox;
use App\Entity\Currency;
use App\Entity\ExchangeOffice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CashboxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt')
            ->add('updatedAt')
            ->add('exchangeOffice', EntityType::class, [
                'label' => 'Обменные пункты',
                'class' => ExchangeOffice::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите обменный пункт'
            ])
            ->add('currency', EntityType::class, [
                'label' => 'Тип валюты',
                'class' => Currency::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите тип валют'
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cashbox::class,
        ]);
    }
}
