<?php

declare(strict_types=1);

namespace App\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AddAddressWayCountrySubscriber extends AddAddressWaySubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'addAddressWayFieldOnCountryPostSubmit',
        ];
    }

    public function addAddressWayFieldOnCountryPostSubmit(FormEvent $event): void
    {
        /** @var ?int $country */
        $country = $event->getForm()->getData() ?:
            $this->countryThesaurusProvider->getChoices()['pel.country.france'];
        /** @var FormInterface $parent */
        $parent = $event->getForm()->getParent()?->getParent();
        $this->addAddressWayField($parent, $country);
    }
}
