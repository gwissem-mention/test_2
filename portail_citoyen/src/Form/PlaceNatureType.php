<?php

declare(strict_types=1);

namespace App\Form;

use App\Thesaurus\NaturePlaceThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class PlaceNatureType extends AbstractType
{
    public function __construct(private readonly NaturePlaceThesaurusProviderInterface $naturePlaceThesaurusProvider)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('place', ChoiceType::class, [
                'attr' => [
                    'class' => 'fr-select',
                ],
                'choices' => $this->naturePlaceThesaurusProvider->getChoices(),
                'label' => 'nature.place',
                'label_attr' => [
                    'class' => 'fr-label',
                ],
                'placeholder' => 'nature.place.placeholder',
            ])
        ;
    }
}
