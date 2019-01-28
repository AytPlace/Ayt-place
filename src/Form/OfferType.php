<?php

namespace App\Form;

use App\Entity\Offer;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'offre",
                'required' => true
            ])
            ->add('travelerNumbers', NumberType::class, [
                'required' => true,
                'label' => 'Nombre maximun de voyageur'
            ])
            ->add('costByTraveler', NumberType::class, [
                'required' => true,
                'label' => 'Prix par voyageur'
            ])
            ->add('location', TextType::class, [
                'required' => true,
                'label' => 'Adresse du lieux'
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'label' => 'Ville'
            ])
            ->add('region', ChoiceType::class, [
                'choices' => Offer::REGION,
            ])
            ->add('country', TextType::class, [
                'required' => true,
                'label' => 'Pays'
            ])
            ->add('description', CKEditorType::class, [
                'required' => true,
                'label' => 'Description',
                'config' => ['toolbar' => 'standard']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
