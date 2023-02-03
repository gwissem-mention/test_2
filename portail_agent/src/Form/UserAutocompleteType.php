<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserAutocompleteType extends AbstractType
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
                ['alias' => 'user']
            ),
            'label' => 'pel.agent.name',
            'placeholder' => false,
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
