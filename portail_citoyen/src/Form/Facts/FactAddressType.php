<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Etalab\AddressEtalabHandler;
use App\Etalab\AddressZoneChecker;
use App\Form\AddressEtalabType;
use App\Form\Model\Address\AddressEtalabModel;
use App\Form\Model\EtalabInput;
use App\Form\Model\Facts\FactAddressModel;
use App\Form\Validator\EtalabAddressValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class FactAddressType extends AbstractType
{
    private const NATURE_PLACE_TRANSPORTS = 'Transports';

    public function __construct(
        private readonly AddressEtalabHandler $addressEtalabHandler,
        private readonly AddressZoneChecker $addressZoneChecker,
        private readonly EtalabAddressValidator $etalabAddressValidator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addressAdditionalInformation', TextareaType::class, [
                'label' => 'pel.additional.place.information',
                'required' => false,
                'help' => self::NATURE_PLACE_TRANSPORTS === $options['nature_place'] ? 'pel.transport.additional.place.information' : 'pel.additional.place.information.help',
            ]);

        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var ?FactAddressModel $addressModel */
                    $addressModel = $event->getData();
                    $this->addAddressOrRouteFactsKnown($event->getForm(), $addressModel);
                    $this->addOffenseNatureOrNotKnownField(
                        $event->getForm(),
                        $addressModel?->isAddressOrRouteFactsKnown()
                    );
                }
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    /** @var array<string, mixed> $data */
                    $data = $event->getData();
                    $this->addAddressOrRouteFactsKnown($event->getForm());
                    $this->addOffenseNatureOrNotKnownField($event->getForm(), isset($data['addressOrRouteFactsKnown']) ? (bool) $data['addressOrRouteFactsKnown'] : null);
                }
            );
    }

    private function addAddressOrRouteFactsKnown(FormInterface $form, FactAddressModel $factAddressModel = null): void
    {
        if (
            false === $form->getConfig()->getOption('address_or_route_facts_known_show')
            || false === $form->getConfig()->getOption('addresses_show')
        ) {
            $form->remove('addressOrRouteFactsKnown')->remove('startAddress')->remove('endAddress');
            $factAddressModel?->setAddressOrRouteFactsKnown(null)->setStartAddress(null)->setEndAddress(null);

            return;
        }

        $form->add('addressOrRouteFactsKnown', ChoiceType::class, [
            'label' => 'pel.address.or.route.facts',
            'expanded' => true,
            'multiple' => false,
            'inline' => true,
            'choices' => [
                'pel.yes' => true,
                'pel.no' => false,
            ],
            'constraints' => [
                new NotNull(),
            ],
        ]);
    }

    private function addOffenseNatureOrNotKnownField(
        FormInterface $form,
        ?bool $choice,
        FactAddressModel $addressModel = null,
    ): void {
        $naturePlace = $form->getConfig()->getOption('nature_place');
        $startAddressLabel = $form->getConfig()->getOption('start_address_label');
        $endAddressLabel = $form->getConfig()->getOption('end_address_label');
        $startAddressShow = $form->getConfig()->getOption('start_address_show');
        $endAddressShow = $form->getConfig()->getOption('end_address_show');
        if (true === $choice || false === $form->getConfig()->getOption('address_or_route_facts_known_show')) {
            $endAddressConstraints = [
                new Callback([$this, 'validateAddresses']),
                new Callback([
                    'callback' => function (?string $value, ExecutionContextInterface $context) {
                        $this->etalabAddressValidator->validate($value, $context);
                    },
                ]),
            ];
            if (self::NATURE_PLACE_TRANSPORTS === $naturePlace) {
                $endAddressConstraints[] = new NotBlank();
            }

            if (true === $startAddressShow) {
                $form
                    ->add('startAddress', AddressEtalabType::class, [
                        'label' => $startAddressLabel ?? ($endAddressShow ? 'pel.address.start.or.exact' : 'pel.address.exact'),
                        'help' => $endAddressShow && self::NATURE_PLACE_TRANSPORTS != $naturePlace ? 'pel.address.start.or.exact.help' : null,
                        'address_constraints' => [
                            new NotBlank(),
                            new Callback([$this, 'validateAddresses']),
                            new Callback([
                                'callback' => function (?string $value, ExecutionContextInterface $context) {
                                    $this->etalabAddressValidator->validate($value, $context);
                                },
                            ]),
                        ],
                    ]);
            }

            if (true === $endAddressShow) {
                $form->add('endAddress', AddressEtalabType::class, [
                    'required' => self::NATURE_PLACE_TRANSPORTS === $naturePlace,
                    'label' => $endAddressLabel ?? 'pel.address.end',
                    'help' => self::NATURE_PLACE_TRANSPORTS != $naturePlace ? 'pel.address.end.help' : null,
                    'address_constraints' => $endAddressConstraints,
                ]);
            }
        } else {
            $form
                ->remove('startAddress')
                ->remove('endAddress');

            $addressModel?->setStartAddress(null)->setEndAddress(null);
        }
    }

    public function validateAddresses(?string $address, ExecutionContextInterface $context): void
    {
        /** @var Form $form */
        $form = $context->getObject();
        /** @var Form $formParent */
        $formParent = $form->getParent()?->getParent();

        if (!$formParent->has('startAddress') || !$formParent->has('endAddress')) {
            return;
        }

        /** @var array<string, string|null> $startAddress */
        $startAddress = $formParent->get('startAddress')->getData();
        /** @var array<string, string|null> $endAddress */
        $endAddress = $formParent->get('endAddress')->getData();

        if (null !== $startAddress['address'] && $endAddress['address']) {
            $startAddressEtalab = $this->addressEtalabHandler->getAddressModel(new EtalabInput($startAddress['address'], $startAddress['selectionId'] ?? '', $startAddress['query'] ?? ''));
            $endAddressEtalab = $this->addressEtalabHandler->getAddressModel(new EtalabInput($endAddress['address'], $endAddress['selectionId'] ?? '', $endAddress['query'] ?? ''));
            $startAddressDepartmentNumber = $endAddressDepartmentNumber = null;

            if ($startAddressEtalab instanceof AddressEtalabModel) {
                $startAddressDepartmentNumber = $startAddressEtalab->getPostcode() ? substr($startAddressEtalab->getPostcode(), 0, 2) : null;
            }
            if ($endAddressEtalab instanceof AddressEtalabModel) {
                $endAddressDepartmentNumber = $endAddressEtalab->getPostcode() ? substr($endAddressEtalab->getPostcode(), 0, 2) : null;
            }

            if (
                $startAddressDepartmentNumber && $endAddressDepartmentNumber
                && !$this->addressZoneChecker->isInsideGironde($startAddressDepartmentNumber)
                && !$this->addressZoneChecker->isInsideGironde($endAddressDepartmentNumber)
            ) {
                $context
                    ->buildViolation('Uniquement les trajets vers la Gironde ou depuis la Gironde sont acceptés')
                    ->addViolation();
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FactAddressModel::class,
            'nature_place' => null,
            'start_address_label' => null,
            'end_address_label' => null,
            'address_or_route_facts_known_show' => true,
            'addresses_show' => true,
            'start_address_show' => true,
            'end_address_show' => true,
        ]);
    }
}
