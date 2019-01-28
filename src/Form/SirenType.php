<?php

namespace App\Form;

use App\Entity\Recipient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SirenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sirenPicture', FileType::class, [
                'label' => 'Justificatif Siren',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    'maxSize' => '500MO',
                    'maxSizeMessage' => 'form.file.size',
                    'mimeTypes' => ['application/pdf', 'application/x-pdf'],
                    'mimeTypesMessage' => "form.file.types"
                ],
                'attr' => [
                    'accept' => '.pdf'
                ]
            ])
            ->add('identityCardPicture', FileType::class, [
                'label' => "Justificatif carte d'identité",
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    'maxSize' => '500MO',
                    'maxSizeMessage' => 'form.file.size',
                    'mimeTypes' => ['application/pdf', 'application/x-pdf'],
                    'mimeTypesMessage' => "form.file.types"
                ],
                'attr' => [
                    'accept' => '.pdf'
                ]
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
