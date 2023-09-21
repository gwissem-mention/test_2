<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Form\Model\Facts\FactsModel;
use App\Form\PhoneType;
use App\Referential\Entity\NaturePlace;
use App\Referential\Repository\NaturePlaceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class FactsType extends AbstractType
{
    private const NATURE_PLACE_TELEPHONE = 'Téléphone';
    private const NATURE_PLACE_PARKING = 'Parking';
    private const NATURE_PLACE_INTERNET = 'Internet';
    private const NATURE_PLACE_WORSHIP = 'Lieu de culte ou de recueillement';
    private const NATURE_PLACE_LEISURE = 'Lieu de loisirs';

    private const NATURES_PLACES_ADDRESS_OR_ROUTE_FACTS_KNOWN_NOT_DISPLAYED = [
        self::NATURE_PLACE_PARKING,
        self::NATURE_PLACE_WORSHIP,
        self::NATURE_PLACE_LEISURE,
    ];

    private const NATURES_PLACES_ADDRESSES_NOT_DISPLAYED = [
        self::NATURE_PLACE_TELEPHONE,
        self::NATURE_PLACE_INTERNET,
    ];

    // For other uses
    private const NATURES_PLACES_START_ADDRESS_NOT_DISPLAYED = [];

    private const NATURES_PLACES_END_ADDRESS_NOT_DISPLAYED = [
        self::NATURE_PLACE_PARKING,
        self::NATURE_PLACE_WORSHIP,
        self::NATURE_PLACE_LEISURE,
    ];

    public function __construct(
        private readonly NaturePlaceRepository $naturePlaceRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'min' => 30,
                        'max' => 1500,
                    ]),
                    new NotBlank(),
                ],
                'label' => 'pel.facts.description.precise',
                'attr' => [
                    'class' => 'fr-input',
                    'data-counter-target' => 'parent',
                    'minlength' => 30,
                    'maxlength' => 1500,
                ],
            ])
            ->add('victimOfViolence', CheckboxType::class, [
                'label' => 'pel.victim.of.violence',
                'help' => 'pel.victim.of.violence.help',
                'required' => false,
            ])
            ->add('placeNature', ChoiceType::class, [
                'choices' => $this->naturePlaceRepository->getNaturePlacesChoices(),
                'label' => 'pel.nature.place',
                'placeholder' => 'pel.nature.place.choose',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('offenseDate', OffenseDateType::class, [
                'label' => false,
                'required' => true,
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var FactsModel $factsModel */
                    $factsModel = $event->getData();
                    $form = $event->getForm();
                    $this->addAddressField($form, $factsModel->getPlaceNature());
                    $this->addVictimOfViolenceField($form, $factsModel->isVictimOfViolence());
                    $this->addSubNaturePlaceField($form, $factsModel->getPlaceNature(), $factsModel);
                    $this->addCallingPhoneField($form, $factsModel->getPlaceNature(), $factsModel);
                    $this->addWebsiteField($form, $factsModel->getPlaceNature(), $factsModel);
                }
            );

        $builder->get('victimOfViolence')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var bool $victimOfViolence */
                $victimOfViolence = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var FactsModel $factsModel */
                $factsModel = $parent->getData();
                $this->addVictimOfViolenceField($parent, boolval($victimOfViolence), $factsModel);
            }
        );

        $builder->get('placeNature')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var int $naturePlace */
                $naturePlace = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var FactsModel $factsModel */
                $factsModel = $parent->getData();
                $this->addAddressField($parent, $naturePlace);
                $this->addSubNaturePlaceField($parent, $naturePlace, $factsModel);
                $this->addCallingPhoneField($parent, $naturePlace, $factsModel);
                $this->addWebsiteField($parent, $naturePlace, $factsModel);
            }
        );
    }

    private function addAddressField(
        FormInterface $form,
        ?int $naturePlaceId,
    ): void {
        $naturePlace = null;
        if (null !== $naturePlaceId) {
            $naturePlace = $this->naturePlaceRepository->find($naturePlaceId);
        }

        $form->add('address', FactAddressType::class, [
            'label' => false,
            'compound' => true,
            'address_or_route_facts_known_show' => null === $naturePlace || !in_array($naturePlace->getLabel(), self::NATURES_PLACES_ADDRESS_OR_ROUTE_FACTS_KNOWN_NOT_DISPLAYED),
            'addresses_show' => null === $naturePlace || !in_array($naturePlace->getLabel(), self::NATURES_PLACES_ADDRESSES_NOT_DISPLAYED),
            'start_address_show' => null === $naturePlace || !in_array($naturePlace->getLabel(), self::NATURES_PLACES_START_ADDRESS_NOT_DISPLAYED),
            'end_address_show' => null === $naturePlace || !in_array($naturePlace->getLabel(), self::NATURES_PLACES_END_ADDRESS_NOT_DISPLAYED),
        ]);
    }

    private function addVictimOfViolenceField(
        FormInterface $form,
        ?bool $victimOfViolence,
        FactsModel $factsModel = null
    ): void {
        if (true === $victimOfViolence) {
            $form->add('victimOfViolenceText', TextareaType::class, [
                'label' => 'pel.victim.of.violence.text',
                'attr' => [
                    'placeholder' => 'pel.victim.of.violence.text.placeholder',
                    'maxlength' => 250,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 250,
                    ]),
                ],
            ]);
        } else {
            $form->remove('victimOfViolenceText');
            $factsModel?->setVictimOfViolenceText(null);
        }
    }

    private function addSubNaturePlaceField(
        FormInterface $form,
        ?int $naturePlaceId,
        FactsModel $factsModel = null
    ): void {
        if (null === $naturePlaceId) {
            $this->removeSubPlaceNature($form, $factsModel);

            return;
        }

        /** @var NaturePlace $naturePlace */
        $naturePlace = $this->naturePlaceRepository->find($naturePlaceId);

        if (!$naturePlace->getChildren()->isEmpty()) {
            $form->add('subPlaceNature', ChoiceType::class, [
                'choices' => $this->naturePlaceRepository->getNaturePlacesChoices($naturePlaceId),
                'label' => 'pel.precision.nature.place',
                'placeholder' => 'pel.please.specify.nature.place',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
        } else {
            $this->removeSubPlaceNature($form, $factsModel);
        }
    }

    private function removeSubPlaceNature(FormInterface $form, FactsModel $factsModel = null): void
    {
        $form->remove('subPlaceNature');
        $factsModel?->setSubPlaceNature(null);
    }

    private function addCallingPhoneField(
        FormInterface $form,
        ?int $naturePlaceId,
        FactsModel $factsModel = null
    ): void {
        if (null === $naturePlaceId) {
            $this->removeCallingPhoneField($form, $factsModel);

            return;
        }

        /** @var NaturePlace $naturePlace */
        $naturePlace = $this->naturePlaceRepository->find($naturePlaceId);

        if (self::NATURE_PLACE_TELEPHONE !== $naturePlace->getLabel()) {
            $this->removeCallingPhoneField($form, $factsModel);

            return;
        }

        $form->add('callingPhone', PhoneType::class, [
            'label' => false,
            'number_label' => 'pel.fill.the.calling.phone.number',
            'number_required' => false,
        ]);
    }

    private function removeCallingPhoneField(FormInterface $form, FactsModel $factsModel = null): void
    {
        $form->remove('callingPhone');
        $factsModel?->setCallingPhone(null);
    }

    private function addWebsiteField(
        FormInterface $form,
        ?int $naturePlaceId,
        FactsModel $factsModel = null
    ): void {
        if (null === $naturePlaceId) {
            $this->removeWebsiteField($form, $factsModel);

            return;
        }

        /** @var NaturePlace $naturePlace */
        $naturePlace = $this->naturePlaceRepository->find($naturePlaceId);

        if (self::NATURE_PLACE_INTERNET !== $naturePlace->getLabel()) {
            $this->removeWebsiteField($form, $factsModel);

            return;
        }

        $form->add('website', TextType::class, [
            'label' => 'pel.fill.website.or.app',
            'required' => false,
        ]);
    }

    private function removeWebsiteField(FormInterface $form, FactsModel $factsModel = null): void
    {
        $form->remove('website');
        $factsModel?->setWebsite(null);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FactsModel::class,
            'attr' => [
                'novalidate' => true,
            ],
        ]);
    }
}
