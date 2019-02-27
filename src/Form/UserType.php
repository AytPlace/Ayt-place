<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => "Nom",
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => "Prénom",
                'required' => true
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'required' => true
            ])
            ->add('country', TextType::class, [
                'label' => "Pays",
                'required' => true
            ])
            ->add('bornDate', DateTimeType::class, [
                'label' => "Date de naissance",
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => "Numéro de téléphone",
                'required' => true
            ])
            ->add('zipcode', TextType::class, [
                'label' => "Code postal",
                'required' => true
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre',
                'label_attr' => [
                    'class' => 'form__label'
                ],
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'monsieur' => 'M',
                    'madame' => 'Mme',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Email",
                'required' => true
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'use_password' => false
        ]);
    }
}
