<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\DataTransformer\TownToTownAndDepartmentTransformer;
use App\Thesaurus\CountryThesaurusProviderInterface;
use App\Thesaurus\TownAndDepartmentThesaurusProviderInterface;
use App\Thesaurus\WayThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactInformationType extends AbstractType
{
    public function __construct(
        private readonly CountryThesaurusProviderInterface $countryThesaurusProvider,
        private readonly TownAndDepartmentThesaurusProviderInterface $townAndDepartmentAndDepartmentThesaurusProvider,
        private readonly TownToTownAndDepartmentTransformer $townToTownAndDepartmentTransformer,
        private readonly WayThesaurusProviderInterface $wayThesaurusProvider,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addressCountry', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->countryThesaurusProvider->getChoices(),
                'label' => 'address.country',
            ])
            ->add('addressNumber', TextType::class, [
                'attr' => [
                    'maxlength' => 11,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 11]),
                ],
                'label' => 'address.number',
            ])
            ->add('addressWay', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->wayThesaurusProvider->getChoices(),
                'label' => 'address.way',
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'maxlength' => 50,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 50]),
                    new Email(),
                ],
                'label' => 'email',
            ])
            ->add('mobile', TextType::class, [
                'attr' => [
                    'maxlength' => 15,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 15]),
                ],
                'label' => 'mobile',
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var array<string, int> $townParis */
                $townParis = $this->townAndDepartmentAndDepartmentThesaurusProvider->getChoices()['town.paris'];
                /** @var ?int $birthTown */
                $birthTown = $townParis['value'];
                $this->addAddressTownField($event->getForm(), $birthTown);
            }
        );

        $builder->get('addressCountry')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var ?int $birthCountry */
                $birthCountry = $event->getForm()->getData() ?: $this->countryThesaurusProvider->getChoices()['country.france'];
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addAddressTownField($parent, $birthCountry);
            }
        );
    }

    public function addAddressTownField(FormInterface $form, ?int $birthCountry): void
    {
        $addressCities = null === $birthCountry ? [] : $this->getAvailableAddressTownChoices($birthCountry);

        if (0 === count(array_filter($addressCities))) {
            $form
                ->add('addressTown', TextType::class, [
                    'attr' => [
                        'maxlength' => 50,
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 50]),
                    ],
                    'label' => 'address.town',
                ])
                ->remove('addressDepartment');
        } else {
            $form
                ->add('addressTown', ChoiceType::class, [
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'choices' => $addressCities,
                    'label' => 'address.town',
                    'placeholder' => 'choose.your.address.town',
                ])
                ->add('addressDepartment', TextType::class, [
                    'disabled' => true,
                    'label' => 'address.department',
                ]);
        }
    }

    /**
     * @return array<int|string, int|string>
     */
    private function getAvailableAddressTownChoices(int $country): array
    {
        $values = $this->countryThesaurusProvider->getChoices();

        /** @var array<int|string> $towns */
        $towns = $values['country.france'] === $country ? $this->townToTransform(
            $this->townAndDepartmentAndDepartmentThesaurusProvider->getChoices()
        ) : [''];

        return array_combine($towns, $towns);
    }

    /**
     * @param array<string, mixed> $towns
     *
     * @return array<string, mixed>
     */
    private function townToTransform(array $towns): array
    {
        $townsTransformed = [];

        /**
         * @var array<string, int> $town
         */
        foreach ($towns as $key => $town) {
            $townsTransformed[$key] = $this->townToTownAndDepartmentTransformer->transform([$key, $town['department']]);
        }

        return $townsTransformed;
    }
}
