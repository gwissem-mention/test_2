<?php

declare(strict_types=1);

namespace App\Form;

use App\Thesaurus\NaturePlacePublicTransportThesaurusProviderInterface;
use App\Thesaurus\NaturePlaceThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class PlaceNatureType extends AbstractType
{
    public function __construct(
        private readonly NaturePlaceThesaurusProviderInterface $naturePlaceThesaurusProvider,
        private readonly NaturePlacePublicTransportThesaurusProviderInterface $naturePlacePublicTransportThesaurusProvider,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('place', ChoiceType::class, [
                'choices' => $this->naturePlaceThesaurusProvider->getChoices(),
                'label' => 'nature.place',
                'placeholder' => 'nature.place.placeholder',
            ])
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var ?int $naturePlacePublicTransport */
                $naturePlacePublicTransport = $event->getData();
                $this->addNaturePlacePublicTransportField($event->getForm(), $naturePlacePublicTransport);
            }
        );

        $builder->get('place')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var ?int $naturePlacePublicTransport */
                $naturePlacePublicTransport = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addNaturePlacePublicTransportField($parent, $naturePlacePublicTransport);
            }
        );
    }

    public function addNaturePlacePublicTransportField(FormInterface $form, ?int $naturePlacePublicTransport): void
    {
        $naturePlaceChoices = null === $naturePlacePublicTransport ? [] : $this->getAvailableNaturePlaceChoices($naturePlacePublicTransport);

        $naturePlacePublicTransportChoice = $this->naturePlaceThesaurusProvider->getChoices()['nature.place.public.transport'];

        $form->add('naturePlacePublicTransportChoice', ChoiceType::class, [
            'choices' => $naturePlaceChoices,
            'label' => false,
            'placeholder' => 'nature.place.public.transport.placeholder',
            'disabled' => $naturePlacePublicTransportChoice === $naturePlacePublicTransport ? false : 'disabled',
            'invalid_message' => false,
        ]);
    }

    /**
     * @return mixed[]
     */
    private function getAvailableNaturePlaceChoices(int $naturePlacePublicTransport): array
    {
        $values = $this->naturePlaceThesaurusProvider->getChoices();

        return $naturePlacePublicTransport === $values['nature.place.public.transport'] ? $this->naturePlacePublicTransportThesaurusProvider->getChoices() : ['' => null];
    }
}
