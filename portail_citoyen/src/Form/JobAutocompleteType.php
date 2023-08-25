<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class JobAutocompleteType extends AbstractType
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'autocomplete' => true,
            'placeholder' => false,
            'preload' => false,
            'tom_select_options' => [
                'maxOptions' => null,
                'hideSelected' => true,
            ],
            'autocomplete_url' => $this->urlGenerator->generate(
                'ux_entity_autocomplete',
                ['alias' => 'job']
            ),
            'attr' => [
                'required' => true,
                'data-controller' => 'autocomplete',
                'data-load-text' => $this->translator->trans('pel.results.loading'),
            ],
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
