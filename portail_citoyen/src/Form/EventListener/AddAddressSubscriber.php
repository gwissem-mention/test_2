<?php

declare(strict_types=1);

namespace App\Form\EventListener;

use App\Form\AddressEtalabType;
use App\Form\ForeignAddressType;
use App\Form\Model\Identity\EmbedAddressInterface;
use App\Form\Validator\EtalabAddressValidator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AddAddressSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected readonly int $franceCode,
        private readonly EtalabAddressValidator $etalabAddressValidator
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'addAddressField',
            FormEvents::PRE_SUBMIT => 'addAddressFieldSubmit',
        ];
    }

    public function addAddressField(FormEvent $event): void
    {
        /** @var ?EmbedAddressInterface $location */
        $location = $event->getData();
        $form = $event->getForm();
        if (null === $location?->getCountry() || $this->franceCode === $location->getCountry()) {
            $this->addFrenchAddressField($form, $location);
        } else {
            $this->addForeignAddressField($form, $location, $location->isSameAddress());
        }
    }

    public function addAddressFieldSubmit(FormEvent $event): void
    {
        /** @var ?EmbedAddressInterface $location */
        $location = $event->getData();
        $form = $event->getForm();
        $sameAddress = isset($location['sameAddress']) && $location['sameAddress'];

        $country = $location['country'] ?? null;
        if (null === $location || '' === $country || $this->franceCode === (int) $country) {
            $this->addFrenchAddressField($form);
        } else {
            $this->addForeignAddressField($form, disabled: $sameAddress);
        }
    }

    protected function addFrenchAddressField(
        FormInterface $form,
        EmbedAddressInterface $model = null
    ): void {
        $constraints = [
            new NotBlank(),
            new Callback([
                'callback' => function (?string $value, ExecutionContextInterface $context) {
                    $this->etalabAddressValidator->validate($value, $context);
                },
            ]),
        ];

        if ($model instanceof EmbedAddressInterface && $model->isSameAddress()) {
            $constraints = [];
        }

        if ($form->getConfig()->getOption('need_same_address_field')) {
            $form->add('sameAddress', CheckboxType::class, [
                'label' => 'pel.same.address.as.declarant',
                'required' => false,
                'attr' => [
                    'data-controller' => 'form',
                    'data-action' => 'form#sameAddress',
                ],
            ]);
        }
        $form
            ->remove('foreignAddress')
            ->add('frenchAddress', AddressEtalabType::class, [
                'address_label' => $form->getConfig()->getOption('need_same_address_field') ? false : 'pel.address',
                'address_constraints' => $constraints,
                'address_data' => $model?->getFrenchAddress()?->getLabel(),
                'required' => true,
                'disabled' => $model?->isSameAddress() ?? false,
            ]);

        $model?->setForeignAddress(null);
    }

    protected function addForeignAddressField(
        FormInterface $form,
        EmbedAddressInterface $model = null,
        bool $disabled = false,
    ): void {
        if ($form->getConfig()->getOption('need_same_address_field')) {
            $form->add('sameAddress', CheckboxType::class, [
                'label' => 'pel.same.address.as.declarant',
                'required' => false,
                'attr' => [
                    'data-action' => 'live#action',
                    'data-action-name' => 'sameAddress',
                ],
            ]);
        }

        $form
            ->remove('frenchAddress')
            ->add('foreignAddress', ForeignAddressType::class, [
                'label' => false,
                'compound' => true,
                'fields_disabled' => $disabled,
            ]);

        $model?->setFrenchAddress(null);
    }
}
