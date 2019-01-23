<?php

namespace App\Form;

use App\Entity\AvailabilityOffer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvailbilityOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateTimeType::class, [
                'date_format' => 'dd-MM-yyyy',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('endDate', DateTimeType::class, [
                'date_format' => 'dd-MM-yyyy',
                'required' => true,
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AvailabilityOffer::class,
        ]);
    }
}
