<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CityAutocompleteType extends AbstractType
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'autocomplete' => true,
            'preload' => false,
            'autocomplete_url' => $this->urlGenerator->generate(
                'ux_entity_autocomplete',
                ['alias' => 'city']
            ),
            'label' => 'pel.birth.town',
            'placeholder' => 'pel.birth.town.placeholder',
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'autocomplete';
    }
}
