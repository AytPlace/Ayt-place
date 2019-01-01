<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder)
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
                'label' => "numéro de téléphone",
                'required' => true
            ])
            ->add('gender', CheckboxType::class, [
                'label' => "Genre",
                'required' => true,

            ])
            ->add('email', EmailType::class, [
                'label' => "Email",
                'required' => true
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'répéter mot de passe ']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
