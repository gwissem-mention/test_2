<?php

declare(strict_types=1);

namespace App\Form\EventListener;

use App\Form\AddressEtalabType;
use App\Form\Model\Identity\EmbedAddressInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddAddressSubscriber implements EventSubscriberInterface
{
    public function __construct(protected readonly int $franceCode)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'addAddressField',
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
            $this->addForeignAddressField($form, $location);
        }
    }

    protected function addFrenchAddressField(
        FormInterface $form,
        EmbedAddressInterface|null $model = null
    ): void {
        $constraints = [
            new NotBlank(),
        ];

        if ($model instanceof EmbedAddressInterface && $model->isSameAddress()) {
            $constraints = [];
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

        $model?->setForeignAddress(null);
    }

    protected function addForeignAddressField(
        FormInterface $form,
        EmbedAddressInterface|null $model = null
    ): void {
        $form
             ->remove('frenchAddress')
            ->add('foreignAddress', TextType::class, [
                'label' => $form->getConfig()->getOption('need_same_address_field') ? false : 'pel.address',
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'class' => 'fr-input',
                ],
                'disabled' => $model?->isSameAddress() ?? false,
            ]);

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

        $model?->setFrenchAddress(null);
    }
}
