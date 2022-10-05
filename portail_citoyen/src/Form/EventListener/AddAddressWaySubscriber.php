<?php

declare(strict_types=1);

namespace App\Form\EventListener;

use App\Thesaurus\CountryThesaurusProviderInterface;
use App\Thesaurus\WayThesaurusProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddAddressWaySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly CountryThesaurusProviderInterface $countryThesaurusProvider,
        private readonly WayThesaurusProviderInterface $wayThesaurusProvider,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::SUBMIT => 'submit',
        ];
    }

    public function preSetData(FormEvent $event): void
    {
        /** @var int $country */
        $country = $this->countryThesaurusProvider->getChoices()['pel.country.france'];
        $this->addAddressWayField(
            $event->getForm(),
            $country
        );
    }

    public function submit(FormEvent $event): void
    {
        /** @var array<string, string> $data */
        $data = (array) $event->getData();
        /** @var array<string> $addressLocation */
        $addressLocation = (array) $data['addressLocation'];
        if (isset($addressLocation['country'])) {
            /** @var ?int $country */
            $country = $addressLocation['country'] ?:
                $this->countryThesaurusProvider->getChoices()['pel.country.france'];

            $this->addAddressWayField($event->getForm(), $country);
        }
    }

    private function addAddressWayField(FormInterface $form, null|int $country): void
    {
        if ($this->countryThesaurusProvider->getChoices()['pel.country.france'] === $country) {
            $this->addFrenchAddressWayField($form);
        } else {
            $this->addForeignAddressWayField($form);
        }
    }

    private function addFrenchAddressWayField(FormInterface $form): void
    {
        $form->add('addressWay', ChoiceType::class, [
            'constraints' => [
                new NotBlank(),
            ],
            'choices' => $this->wayThesaurusProvider->getChoices(),
            'label' => 'pel.address.way',
        ]);
    }

    private function addForeignAddressWayField(FormInterface $form): void
    {
        $form
            ->add('addressWay', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'pel.address.way',
            ]);
    }
}
