<?php

namespace App\Form;

use App\Entity\Recipient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => "NOM",
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => "PRENOM",
                'required' => true
            ])
            ->add('city', TextType::class, [
                'label' => "VILLE",
                'required' => true
            ])
            ->add('zipcode', TextType::class, [
                'label' => "CODE POSTAL",
                'required' => true
            ])
            ->add('country', TextType::class, [
                'label' => "PAYS",
                'required' => true
            ])
            ->add('bornDate', DateTimeType::class, [
                'label' => "DATE DE NAISSANCE",
                'required' => true,
                'widget' => 'single_text'
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => "NUMERO DE TELEPHONE",
                'required' => true
            ])
            ->add('gender', ChoiceType::class, [
                'label' => false,
                'label_attr' => [
                    'class' => 'form__label'
                ],
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'MONSIEUR' => 'M',
                    'MADAME' => 'Mme',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "EMAIL",
                'required' => true
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'MOT DE PASSE'],
                'second_options' => ['label' => 'REPETER MOT DE PASSE ']
            ])
            ->add('siren', TextType::class, [
                'required' => true,
                'label' => 'SIREN'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipient::class,
            'use_password' => false
        ]);
    }
}
