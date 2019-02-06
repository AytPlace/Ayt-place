<?php

namespace App\Form;

use App\Entity\AvailabilityOffer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
                    'data-date-format' => "yyyy/mm/dd",
                    'class' => 'datepicker-here'
                ]
            ])
        ;
    }
}
