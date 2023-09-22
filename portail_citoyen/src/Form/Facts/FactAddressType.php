<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Form\AddressEtalabType;
use App\Form\Model\Facts\FactAddressModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class FactAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addressAdditionalInformation', TextareaType::class, [
                'label' => 'pel.additional.place.information',
                'required' => false,
                'help' => 'pel.additional.place.information.help',
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
        $startAddressLabel = $form->getConfig()->getOption('start_address_label');
        $startAddressShow = $form->getConfig()->getOption('start_address_show');
        $endAddressShow = $form->getConfig()->getOption('end_address_show');
        if (true === $choice || false === $form->getConfig()->getOption('address_or_route_facts_known_show')) {
            if (true === $startAddressShow) {
                $form
                    ->add('startAddress', AddressEtalabType::class, [
                        'label' => $startAddressLabel ?? ($endAddressShow ? 'pel.address.start.or.exact' : 'pel.address.exact'),
                        'help' => $endAddressShow ? 'pel.address.start.or.exact.help' : null,
                        'constraints' => [
                            new NotBlank(),
                        ],
                    ]);
            }

            if (true === $endAddressShow) {
                $form->add('endAddress', AddressEtalabType::class, [
                    'required' => false,
                    'label' => 'pel.address.end',
                    'help' => 'pel.address.end.help',
                ]);
            }
        } else {
            $form
                ->remove('startAddress')
                ->remove('endAddress');

            $addressModel?->setStartAddress(null)->setEndAddress(null);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FactAddressModel::class,
            'start_address_label' => null,
            'address_or_route_facts_known_show' => true,
            'addresses_show' => true,
            'start_address_show' => true,
            'end_address_show' => true,
        ]);
    }
}
