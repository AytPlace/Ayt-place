<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientForgotPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => ['autocapitalize' => 'off']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ->getForm();
    }
}
