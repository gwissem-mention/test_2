<?php

declare(strict_types=1);

namespace App\Form;

use App\Referential\Provider\Country\CachedCountryProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;

#[AsEntityAutocompleteField]
class CountryAutocompleteType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CachedCountryProvider $countryProvider
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'autocomplete' => true,
            'placeholder' => 'France',
            'preload' => false,
            'choices' => $this->countryProvider->getChoices(),
            'constraints' => [
                new NotBlank(),
            ],
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
