<?php

declare(strict_types=1);

namespace App\Form;

use App\Thesaurus\NaturePlaceOtherThesaurusProviderInterface;
use App\Thesaurus\NaturePlacePublicTransportThesaurusProviderInterface;
use App\Thesaurus\NaturePlaceThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;
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
                'label' => 'pel.nature.place',
                'placeholder' => 'pel.nature.place.choice.message',
                'constraints' => [
                    new NotBlank(message: 'pel.nature.place.not.blank.error'),
                ],
            ])
            ->add('moreInfo', CheckboxType::class, [
                'label' => 'pel.more.info.place',
                'required' => false,
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

        $builder->get('moreInfo')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var bool $moreInfoValue */
                $moreInfoValue = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addMoreInfoText($parent, $moreInfoValue);
            }
        );
    }

    private function addNaturePlacePublicTransportField(FormInterface $form, ?int $place): void
    {
        $naturePlaces = $this->naturePlaceThesaurusProvider->getChoices();

        if ($place === $naturePlaces['pel.nature.place.public.transport']) {
            $choices = null === $place ? [] : $this->getAvailableNaturePlaceChoices(
                $place
            );

            $form->add('naturePlacePublicTransportChoice', ChoiceType::class, [
                'choices' => $choices,
                'label' => false,
                'placeholder' => 'pel.nature.place.public.transport.choice.message',
            ]);
        }
    }

    private function addNaturePlaceOtherField(FormInterface $form, ?int $place): void
    {
        $naturePlaces = $this->naturePlaceThesaurusProvider->getChoices();

        if ($place === $naturePlaces['pel.nature.place.other']) {
            $choices = null === $place ? [] : $this->getAvailableNaturePlaceChoices(
                $place
            );
            $form->add('naturePlaceOtherChoice', ChoiceType::class, [
                'choices' => $choices,
                'label' => false,
                'placeholder' => 'pel.nature.place.other.choice.message',
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
            $values['pel.nature.place.public.transport'] => $this->naturePlacePublicTransportThesaurusProvider->getChoices(
            ),
            $values['pel.nature.place.other'] => $this->naturePlaceOtherThesaurusProvider->getChoices(),
            default => []
        };
    }

    private function addMoreInfoText(FormInterface $form, bool $moreInfoValue): void
    {
        if (true === $moreInfoValue) {
            $form->add('moreInfoText', TextType::class, [
                'label' => false,
                'constraints' => [
                    new Length([
                        'max' => 150,
                    ]),
                ],
            ]);
        }
    }
}
