<?php

declare(strict_types=1);

namespace App\Form;

use App\Referential\Entity\Unit;
use App\Referential\Repository\UnitRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;

#[AsEntityAutocompleteField]
class UnitAutocompleteType extends AbstractType
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UnitRepository $unitRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addModelTransformer(new CallbackTransformer(
                function (?string $unitName) {
                    return $this->unitRepository->findOneBy(['name' => $unitName])?->getCode();
                },
                function (?string $unitCode) {
                    return $unitCode;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Unit::class,
            'autocomplete' => true,
            'preload' => false,
            'autocomplete_url' => $this->urlGenerator->generate('ux_entity_autocomplete', [
                'alias' => 'unit',
            ]),
            'label' => 'pel.unit.name',
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
