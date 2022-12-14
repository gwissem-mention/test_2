<?php

declare(strict_types=1);

namespace App\Form\EventListener;

use App\Form\AddressEtalabType;
use App\Form\Model\Identity\EmbedAddressInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
        $form
            ->remove('foreignAddress')
            ->add('frenchAddress', AddressEtalabType::class, [
                'address_label' => 'pel.address',
                'address_constraints' => [
                    new NotBlank(),
                ],
                'address_data' => $model?->getFrenchAddress()?->getLabel(),
                'required' => true,
            ]);

        $model?->setForeignAddress(null);
    }

    protected function addForeignAddressField(
        FormInterface $form,
        EmbedAddressInterface|null $model = null
    ): void {
        $form
            ->remove('frenchAddress')
            ->add('foreignAddress', TextType::class, [
                'label' => 'pel.address',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        $model?->setFrenchAddress(null);
    }
}
