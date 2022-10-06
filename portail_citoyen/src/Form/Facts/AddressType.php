<?php

declare(strict_types=1);

namespace App\Form\Facts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isAddressFactsKnown', ChoiceType::class, [
                'label' => 'pel.address.facts',
                'expanded' => true,
                'multiple' => false,
                'inline' => true,
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
            ]);

        $builder
            ->get('isAddressFactsKnown')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $choice = $event->getData();
                    if ('' === $choice) {
                        return;
                    }
                    /** @var FormInterface $parent */
                    $parent = $event->getForm()->getParent();
                    $this->addOffenseNatureOrNotKnownField($parent, boolval($choice));
                }
            )
        ;
    }

    private function addOffenseNatureOrNotKnownField(FormInterface $form, bool $choice): void
    {
        if (true === $choice) {
            $form
                ->add('placeNature', PlaceNatureType::class, [
                    'label' => false,
                ]);
        } else {
            $form
                ->add('addressNotKnown', TextareaType::class, [
                    'label' => false,
                ]);
        }
    }
}
