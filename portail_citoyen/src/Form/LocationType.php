<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Model\LocationModel;
use App\Referential\Repository\CityRepository;
use App\Session\SessionHandler;
use Symfony\Component\Form\AbstractType;
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
        private readonly int $franceCode,
        private readonly SessionHandler $sessionHandler,
        private readonly CityRepository $cityRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ?LocationModel $locationModel */
        $locationModel = $this->sessionHandler->getComplaint()?->getIdentity()?->getCivilState()?->getBirthLocation();
        $builder
            ->add('country', CountryAutocompleteType::class, [
                'attr' => [
                    'data-controller' => 'form',
                    'data-action' => 'form#removeFrenchTown',
                    'data-live-id' => 'country-'.microtime(),
                    'aria-hidden' => 'true',
                ],
                'label' => $options['country_label'],
                'preferred_choices' => [$this->franceCode],
                'empty_data' => null === $locationModel?->getCountry() ?
                    $this->franceCode :
                    $locationModel->getCountry(),
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var ?LocationModel $location */
                    $location = $event->getData();
                    $this->addTownField(
                        $event->getForm(),
                        $location?->getCountry(),
                        $location?->getFrenchTown(),
                        $location
                    );
                }
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) use ($locationModel) {
                    /** @var array<string, int|string> $location */
                    $location = $event->getData();
                    $this->addTownField(
                        $event->getForm(),
                        !empty($location['country']) ? intval($location['country']) : null,
                        !empty($location['frenchTown']) ? strval($location['frenchTown']) : null,
                        $locationModel
                    );
                }
            )
            ->get('country')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    /** @var ?int $country */
                    $country = $event->getForm()->getData();
                    /** @var FormInterface $parent */
                    $parent = $event->getForm()->getParent();
                    /** @var ?LocationModel $locationModel */
                    $locationModel = $parent->getData();
                    $this->addTownField(
                        $parent,
                        $country,
                        /* @phpstan-ignore-next-line */
                        $parent->has('frenchTown') ? strval($parent->get('frenchTown')->getData()) : null,
                        $locationModel
                    );
                }
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LocationModel::class,
            'country_label' => false,
            'town_label' => false,
            'department_label' => false,
        ]);
    }

    private function addTownField(
        FormInterface $form,
        int $country = null,
        string $frenchTown = null,
        LocationModel $locationModel = null
    ): void {
        if (null === $country || $this->franceCode === $country) {
            $this->addFormPartForFrenchPlace($form, $frenchTown, $locationModel);
        } else {
            $this->addFormPartForForeignPlace($form, $locationModel);
        }
    }

    private function addFormPartForForeignPlace(FormInterface $form, LocationModel $locationModel = null): void
    {
        $form
            ->remove('frenchTown')
            ->remove('department')
            ->add('otherTown', TextType::class, [
                'attr' => [
                    'maxlength' => 50,
                    'autocomplete' => 'address-level2',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 50]),
                ],
                'label' => $form->getConfig()->getOption('town_label'),
            ]);

        $locationModel?->setFrenchTown(null);
    }

    private function addFormPartForFrenchPlace(
        FormInterface $form,
        string $frenchTown = null,
        LocationModel $locationModel = null
    ): void {
        $choices = [];
        $city = null;

        if (null !== $frenchTown) {
            $city = $this->cityRepository->findOneBy(['inseeCode' => $frenchTown]);
            if (null !== $city) {
                $choices[$city->getLabelAndPostCode()] = $city->getInseeCode();
            }
        }

        $form
            ->remove('otherTown')
            ->add('frenchTown', CityAutocompleteType::class, [
                'data' => $city?->getInseeCode(),
                'choices' => $choices,
                'attr' => [
                    'class' => 'french-town',
                    'autocomplete' => 'address-level2',
                    'data-live-id' => 'city-'.microtime(),
                    'aria-hidden' => 'true',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('department', TextType::class, [
                'disabled' => true,
                'label' => $form->getConfig()->getOption('department_label'),
                'mapped' => false,
            ]);

        $locationModel?->setOtherTown(null);
    }
}
