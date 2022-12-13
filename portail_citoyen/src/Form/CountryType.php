<?php

namespace App\Form;

use App\Referential\Provider\Country\CachedCountryProvider;
use Symfony\Component\Form\ChoiceList\Factory\ChoiceListFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class CountryType extends ChoiceType
{
    private readonly CachedCountryProvider $countryProvider;

    public function __construct(CachedCountryProvider $cachedCountryProvider, ChoiceListFactoryInterface $choiceListFactory = null, TranslatorInterface $translator = null)
    {
        $this->countryProvider = $cachedCountryProvider;

        parent::__construct($choiceListFactory, $translator);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'choices' => $this->countryProvider->getChoices(),
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }
}
