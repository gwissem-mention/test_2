<?php

declare(strict_types=1);

namespace App\Form\Facts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class FactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('offenseNature', OffenseNatureType::class, [
                'label' => false,
            ])
            ->add('placeNature', PlaceNatureType::class, [
                'label' => false,
            ])
            ->add('offenseDate', OffenseDateType::class, [
                'label' => false,
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'max' => 800,
                    ]),
                ],
                'label' => 'pel.facts.description.precise',
            ])
            ->add('objects', LiveCollectionType::class, [
                'entry_type' => ObjectType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'pel.objects',
                'label_attr' => [
                    'class' => 'fr-h6',
                ],
                'button_add_options' => [
                    'label' => 'pel.objects.add',
                    'attr' => [
                        'class' => 'fr-btn fr-btn--secondary',
                    ],
                ],
                'button_delete_options' => [
                    'label' => 'pel.delete',
                    'attr' => [
                        'class' => 'fr-btn fr-btn--tertiary-no-outline',
                    ],
                ],
                'data' => [[]],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('additionalInformation', AdditionalInformationType::class, [
                'label' => false,
            ]);
    }
}
