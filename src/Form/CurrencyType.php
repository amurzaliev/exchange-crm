<?php

namespace App\Form;

use App\Entity\Currency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CurrencyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Название'])
            ->add('imageFile', FileType::class, [
                'label' => 'Иконка',
                'required' => false
            ])
            ->add('iso', TextType::class, ['label' => 'iso-код'])
            ->add('symbolDesignation', TextType::class, ['label' => 'Символьное обозначение'])
            ->add('decimals', NumberType::class, ['label' => 'Колличество знаков после запятой'])

            ->add('decimalSeparator', ChoiceType::class, [
                'choices' => [
                    'Запятая' => ',',
                    'Точка' => '.'
                ]])
            ->add('thousandSeparator', ChoiceType::class, [
                'choices' => [
                    'Запятая' => ',',
                    'Точка' => '.'
                ]])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Currency::class,
        ]);
    }
}
