<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class ContinuationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('materialDamage', ChoiceType::class, [
                'label' => 'pel.complaint.continuation.material.damage',
                'expanded' => true,
                'multiple' => false,
                'inline' => true,
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'choice_attr' => [
                    'pel.no' => [
                        'data-controller' => 'continuation',
                        'data-action' => 'continuation#redirectToHomepage',
                    ],
                ],
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    $this->addOffenseAuthorKnownField($event->getForm());
                }
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    /** @var array<string, int> $data */
                    $data = $event->getData();

                    if (array_key_exists('materialDamage', $data)) {
                        $this->addOffenseAuthorKnownField(
                            $event->getForm(),
                            boolval($data['materialDamage'])
                        );
                    }

                    if (array_key_exists('offenseAuthorKnown', $data)) {
                        $this->addContinueButton(
                            $event->getForm(),
                            boolval($data['offenseAuthorKnown'])
                        );
                    }
                }
            );
    }

    private function addOffenseAuthorKnownField(FormInterface $form, ?bool $materialDamage = null): void
    {
        if (true === $materialDamage) {
            $form->add('offenseAuthorKnown', ChoiceType::class, [
                'label' => 'pel.complaint.continuation.offense.author.known',
                'expanded' => true,
                'multiple' => false,
                'inline' => true,
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'choice_attr' => [
                    'pel.yes' => [
                        'data-controller' => 'continuation',
                        'data-action' => 'continuation#redirectToHomepage',
                    ],
                ],
            ]);
        }
    }

    private function addContinueButton(FormInterface $form, ?bool $offenseAuthorKnown = null): void
    {
        if (false === $offenseAuthorKnown) {
            $form->add('continue', SubmitType::class, [
                'label' => 'pel.keep.going',
            ]);
        }
    }
}