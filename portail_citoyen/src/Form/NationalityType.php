<?php

declare(strict_types=1);

namespace App\Form;

use App\Referential\Provider\Nationality\CachedNationalityProvider;
use Symfony\Component\Form\ChoiceList\Factory\ChoiceListFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class NationalityType extends ChoiceType
{
    private readonly CachedNationalityProvider $nationalityProvider;

    public function __construct(CachedNationalityProvider $cachedNationalityProvider,
        ChoiceListFactoryInterface $choiceListFactory = null,
        TranslatorInterface $translator = null
    ) {
        $this->nationalityProvider = $cachedNationalityProvider;

        parent::__construct($choiceListFactory, $translator);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'choices' => $this->nationalityProvider->getChoices(),
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }
}
