<?php

declare(strict_types=1);

namespace App\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddAddressSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'addAddressField',
        ];
    }

    public function addAddressField(FormEvent $event): void
    {
        /** @var FormInterface $parent */
        $parent = $event->getForm()->getParent();
        if ('FR' === $event->getData()) {
            $this->addFrenchAddressField($parent);
        } else {
            $this->addForeignAddressField($parent);
        }
    }

    private function addFrenchAddressField(FormInterface $form): void
    {
        $form
            ->remove('foreignAddress')
            ->add('frenchAddress', TextType::class, [
                'label' => 'pel.address',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    private function addForeignAddressField(FormInterface $form): void
    {
        $form
            ->remove('frenchAddress')
            ->add('foreignAddress', TextType::class, [
                'label' => 'pel.address',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }
}
