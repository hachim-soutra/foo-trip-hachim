<?php

namespace App\Form;

use App\Entity\Destination;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class DestinationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', null, [
            'required' => false,
            'empty_data' => '',
            'constraints' => [new Assert\NotBlank(['message' => 'Name cannot be blank'])],
        ])
        ->add('description', null, [
            'required' => false,
            'empty_data' => '',
            'constraints' => [new Assert\NotBlank(['message' => 'Description cannot be blank'])],
        ])
        ->add('duration', null, [
            'required' => false,
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Duration cannot be blank',
                ])
            ],
        ])
        ->add('price', NumberType::class, [
            'required' => false,
            'empty_data' => '',
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Price cannot be blank',
                ]),
                new Assert\GreaterThan([
                    'value' => 0,
                    'message' => 'Price must be greater than 0',
                ]),
            ],
        ])
        ->add('image', Type\FileType::class, [
            'label' => 'Image',
            'mapped' => false,
            'empty_data' => '',
            'required' => false,
            'constraints' => $options['data']->getImage() ? [
                new Assert\File([
                    'mimeTypes' => ['image/jpeg', 'image/png'],
                    'mimeTypesMessage' => 'Please upload a valid JPEG or PNG image.',
                ]),
            ] : [
                new Assert\NotBlank(['message' => 'Please upload a JPEG or PNG image.']),
                new Assert\File([
                    'mimeTypes' => ['image/jpeg', 'image/png'],
                    'mimeTypesMessage' => 'Please upload a JPEG or PNG image.',
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Destination::class,
        ]);
    }
}
