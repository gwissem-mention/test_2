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
        protected readonly CountryThesaurusProviderInterface $countryThesaurusProvider,
        private readonly WayThesaurusProviderInterface $wayThesaurusProvider,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'addAddressWayFieldOnPreSetData',
        ];
    }

    public function addAddressWayFieldOnPreSetData(FormEvent $event): void
    {
        /** @var int $country */
        $country = $this->countryThesaurusProvider->getChoices()['pel.country.france'];
        $this->addAddressWayField(
            $event->getForm(),
            $country
        );
    }

    protected function addAddressWayField(FormInterface $form, null|int $country): void
    {
        if ($this->countryThesaurusProvider->getChoices()['pel.country.france'] === $country) {
            $this->addFrenchAddressWayField($form);
        } else {
            $this->addForeignAddressWayField($form);
        }
    }

    protected function addFrenchAddressWayField(FormInterface $form): void
    {
        $form
            ->remove('foreignAddressWay')
            ->add('frenchAddressWay', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->wayThesaurusProvider->getChoices(),
                'label' => 'pel.address.way',
            ]);
    }

    protected function addForeignAddressWayField(FormInterface $form): void
    {
        $form
            ->remove('frenchAddressWay')
            ->add('foreignAddressWay', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'pel.address.way',
            ]);
    }
}
