<?php

namespace App\Form;

use App\Entity\AvailabilityOffer;
use App\Entity\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intervalDate', TextType::class, [
                'attr' => [
                    'id' => 'date-interval',
                    'data-range' => "true",
                    'data-multiple-dates-separator' => ' - ',
                    'data-language' => 'fr',
                    'class' => 'datepicker-here'
                ],
                'mapped' => false,
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Request::class,
        ]);
    }
}
