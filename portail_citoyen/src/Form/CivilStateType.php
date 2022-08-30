<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\DataTransformer\TownToTownAndDepartmentTransformer;
use App\Thesaurus\CountryThesaurusProviderInterface;
use App\Thesaurus\JobThesaurusProviderInterface;
use App\Thesaurus\NationalityThesaurusProviderInterface;
use App\Thesaurus\TownAndDepartmentThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class CivilStateType extends AbstractType
{
    public function __construct(
        private readonly CountryThesaurusProviderInterface $countryThesaurusProvider,
        private readonly TownAndDepartmentThesaurusProviderInterface $townAndDepartmentAndDepartmentThesaurusProvider,
        private readonly NationalityThesaurusProviderInterface $nationalityThesaurusProvider,
        private readonly TownToTownAndDepartmentTransformer $townToTownAndDepartmentTransformer,
        private readonly JobThesaurusProviderInterface $jobThesaurusProvider
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civility', ChoiceType::class, [
                'choices' => [
                    'm' => 1,
                    'mme' => 2,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'civility',
                'placeholder' => 'choose.your.civility',
            ])
            ->add('birthName', TextType::class, [
                'attr' => [
                    'data-controller' => 'form',
                    'data-action' => 'keyup->form#toUpperCase change->form#toUpperCase',
                    'maxlength' => 70,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 70]),
                ],
                'label' => 'birth.name',
            ])
            ->add('firstnames', TextType::class, [
                'attr' => [
                    'maxlength' => 40,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 40]),
                ],
                'label' => 'first.names',
            ])
            ->add('birthDate', DateType::class, [
                'constraints' => [
                    new NotBlank(),
                    new LessThanOrEqual('today'),
                ],
                'format' => 'dd/MM/yyyy',
                'help' => 'birth.date.help',
                'html5' => false,
                'label' => 'birth.date',
                'widget' => 'single_text',
            ])
            ->add('birthCountry', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->countryThesaurusProvider->getChoices(),
                'label' => 'birth.country',
            ])
            ->add('nationality', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->nationalityThesaurusProvider->getChoices(),
                'label' => 'nationality',
            ])
            ->add('job', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->jobThesaurusProvider->getChoices(),
                'label' => 'your.job',
                'placeholder' => 'your.job.placeholder',
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var array<string, int> $townParis */
                $townParis = $this->townAndDepartmentAndDepartmentThesaurusProvider->getChoices()['town.paris'];
                /** @var ?int $birthTown */
                $birthTown = $townParis['value'];
                $this->addBirthTownField($event->getForm(), $birthTown);
            }
        );

        $builder->get('birthCountry')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var ?int $birthCountry */
                $birthCountry = $event->getForm()->getData() ?: $this->countryThesaurusProvider->getChoices()['country.france'];
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addBirthTownField($parent, $birthCountry);
            }
        );
    }

    private function addBirthTownField(FormInterface $form, ?int $birthCountry): void
    {
        $birthTownChoices = array_filter($this->getAvailableBirthTownChoices($birthCountry));

        if (0 === count($birthTownChoices)) {
            $this->addBirthFormPartForForeignBirthPlace($form);
        } else {
            $this->addBirthFormPartForFrenchBirthPlace($form, $birthTownChoices);
        }
    }

    /**
     * @return array<string, string>
     */
    private function getAvailableBirthTownChoices(?int $country = null): array
    {
        if (null === $country) {
            return [];
        }

        $countries = $this->countryThesaurusProvider->getChoices();

        return $countries['country.france'] === $country
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
            $transformedValue = $this->townToTownAndDepartmentTransformer->transform([$key, $town['department']]);
            $townsTransformed[$transformedValue] = $transformedValue;
        }

        return $townsTransformed;
    }

    private function addBirthFormPartForForeignBirthPlace(FormInterface $form): void
    {
        $form
            ->add('birthTown', TextType::class, [
                'attr' => [
                    'maxlength' => 50,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 50]),
                ],
                'label' => 'birth.town',
            ])
            ->remove('birthDepartment');
    }

    /**
     * @param array<string, string> $birthTownChoices
     */
    private function addBirthFormPartForFrenchBirthPlace(FormInterface $form, array $birthTownChoices): void
    {
        $form
            ->add('birthTown', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $birthTownChoices,
                'label' => 'birth.town',
                'placeholder' => 'choose.your.town',
            ])
            ->add('birthDepartment', TextType::class, [
                'disabled' => true,
                'label' => 'birth.department',
            ]);
    }
}
