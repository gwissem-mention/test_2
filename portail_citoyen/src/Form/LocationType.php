<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\DataTransformer\TownToTownAndDepartmentTransformer;
use App\Thesaurus\CountryThesaurusProviderInterface;
use App\Thesaurus\TownAndDepartmentThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LocationType extends AbstractType
{
    public function __construct(
        private readonly CountryThesaurusProviderInterface $countryThesaurusProvider,
        private readonly TownAndDepartmentThesaurusProviderInterface $townAndDepartmentAndDepartmentThesaurusProvider,
        private readonly TownToTownAndDepartmentTransformer $townToTownAndDepartmentTransformer,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countryChoices = $this->countryThesaurusProvider->getChoices();

        $builder
            ->add('country', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $countryChoices,
                'label' => $options['country_label'],
                'empty_data' => $countryChoices['pel.country.france'],
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var array<string, int> $townParis */
                $townParis = $this->townAndDepartmentAndDepartmentThesaurusProvider->getChoices()['pel.town.paris'];
                /** @var ?int $town */
                $town = $townParis['value'];
                $this->addTownField($event->getForm(), $town);
            }
        );

        $builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var ?int $country */
                $country = $event->getForm()->getData() ?: $this->countryThesaurusProvider->getChoices(
                )['pel.country.france'];
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addTownField($parent, $country);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'country_label' => false,
            'town_label' => false,
            'department_label' => false,
        ]);
    }

    private function addTownField(FormInterface $form, ?int $country): void
    {
        $townChoices = array_filter($this->getAvailableTownChoices($country));

        if (0 === count($townChoices)) {
            $this->addFormPartForForeignPlace($form);
        } else {
            $this->addFormPartForFrenchPlace($form, $townChoices);
        }
    }

    /**
     * @return array<string, string>
     */
    private function getAvailableTownChoices(?int $country = null): array
    {
        if (null === $country) {
            return [];
        }

        $countries = $this->countryThesaurusProvider->getChoices();

        return $countries['pel.country.france'] === $country
            ? $this->townToTransform($this->townAndDepartmentAndDepartmentThesaurusProvider->getChoices())
            : [];
    }

    /**
     * @param array<mixed> $towns
     *
     * @return array<string, string>
     */
    private function townToTransform(array $towns): array
    {
        $townsTransformed = [];

        /**
         * @var array<string, int> $town
         */
        foreach ($towns as $key => $town) {
            $transformedValue = $this->townToTownAndDepartmentTransformer->transform([$key, $town['pel.department']]);
            $townsTransformed[$transformedValue] = $transformedValue;
        }

        return $townsTransformed;
    }

    private function addFormPartForForeignPlace(FormInterface $form): void
    {
        $form
            ->add('town', TextType::class, [
                'attr' => [
                    'maxlength' => 50,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 50]),
                ],
                'label' => $form->getConfig()->getOption('town_label'),
            ])
            ->remove('department');
    }

    /**
     * @param array<string, string> $townChoices
     */
    private function addFormPartForFrenchPlace(FormInterface $form, array $townChoices): void
    {
        $form
            ->add('town', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $townChoices,
                'label' => $form->getConfig()->getOption('town_label'),
                'placeholder' => 'pel.choose.your.town',
            ])
            ->add('department', TextType::class, [
                'disabled' => true,
                'label' => $form->getConfig()->getOption('department_label'),
            ]);
    }
}
