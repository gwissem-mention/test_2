<?php

declare(strict_types=1);

namespace App\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AddAddressCountrySubscriber extends AddAddressSubscriber
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
        if ($this->franceCode === $event->getData()) {
            $this->addFrenchAddressField($parent);
        } else {
            $this->addForeignAddressField($parent);
        }
    }
}
