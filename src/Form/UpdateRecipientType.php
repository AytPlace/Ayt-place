<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 10/01/19
 * Time: 20:54
 */

namespace App\Form;


use App\Entity\Recipient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateRecipientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre',
                'label_attr' => [
                    'class' => 'form__label'
                ],
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'monsieur' => 'M',
                    'madame' => 'Mmme',
                ]
            ])
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
                'date_format' => 'dd-MM-yyyy',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => "numéro de téléphone",
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => "Email",
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipient::class,
        ]);
    }
}