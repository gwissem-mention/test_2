<?php

declare(strict_types=1);

namespace App\Form;

use App\Thesaurus\NaturePlaceOtherThesaurusProviderInterface;
use App\Thesaurus\NaturePlacePublicTransportThesaurusProviderInterface;
use App\Thesaurus\NaturePlaceThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlaceNatureType extends AbstractType
{
    public function __construct(
        private readonly NaturePlaceThesaurusProviderInterface $naturePlaceThesaurusProvider,
        private readonly NaturePlacePublicTransportThesaurusProviderInterface $naturePlacePublicTransportThesaurusProvider,
        private readonly NaturePlaceOtherThesaurusProviderInterface $naturePlaceOtherThesaurusProvider,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('place', ChoiceType::class, [
                'choices' => $this->naturePlaceThesaurusProvider->getChoices(),
                'label' => 'nature.place',
                'placeholder' => 'nature.place.placeholder',
                'constraints' => [
                    new NotBlank(message: 'nature.place.not.blank.error'),
                ],
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var ?int $place */
                $place = $event->getData();
                $this->addNaturePlacePublicTransportField($event->getForm(), $place);
                $this->addNaturePlaceOtherField($event->getForm(), $place);
            }
        );

        $builder->get('place')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var ?int $place */
                $place = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addNaturePlacePublicTransportField($parent, $place);
                $this->addNaturePlaceOtherField($parent, $place);
            }
        );
    }

    private function addNaturePlacePublicTransportField(FormInterface $form, ?int $place): void
    {
        $naturePlaces = $this->naturePlaceThesaurusProvider->getChoices();

        if ($place === $naturePlaces['nature.place.public.transport']) {
            $choices = null === $place ? [] : $this->getAvailableNaturePlaceChoices(
                $place
            );

            $form->add('naturePlacePublicTransportChoice', ChoiceType::class, [
                'choices' => $choices,
                'label' => false,
                'placeholder' => 'nature.place.public.transport.placeholder',
            ]);
        }
    }

    private function addNaturePlaceOtherField(FormInterface $form, ?int $place): void
    {
        $naturePlaces = $this->naturePlaceThesaurusProvider->getChoices();

        if ($place === $naturePlaces['nature.place.other']) {
            $choices = null === $place ? [] : $this->getAvailableNaturePlaceChoices(
                $place
            );
            $form->add('naturePlaceOtherChoice', ChoiceType::class, [
                'choices' => $choices,
                'label' => false,
                'placeholder' => 'nature.place.other.placeholder',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
        }
    }

    /**
     * @return mixed[]
     */
    private function getAvailableNaturePlaceChoices(int $place): array
    {
        $values = $this->naturePlaceThesaurusProvider->getChoices();

        return match ($place) {
            $values['nature.place.public.transport'] => $this->naturePlacePublicTransportThesaurusProvider->getChoices(
            ),
            $values['nature.place.other'] => $this->naturePlaceOtherThesaurusProvider->getChoices(),
            default => []
        };
    }
}
