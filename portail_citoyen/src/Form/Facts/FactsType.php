<?php

declare(strict_types=1);

namespace App\Form\Facts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;

class FactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('offenseNature', OffenseNatureType::class, [
                'label' => false,
            ])
            ->add('placeNature', PlaceNatureType::class, [
                'label' => false,
            ])
            ->add('offenseDate', OffenseDateType::class, [
                'label' => false,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'fr-input',
                ],
                'constraints' => [
                    new Length([
                        'max' => 800,
                    ]),
                ],
                'label' => 'pel.facts.description',
            ])
            ->add('suspectsChoice', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => 'yes',
                    'pel.no' => 'no',
                ],
                'expanded' => true,
                'label' => 'pel.do.you.have.informations.on.potential.suspects',
                'multiple' => false,
            ])
        ;

        $builder->get('suspectsChoice')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $choice = $event->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addInformationTextField($parent, $choice);
            }
        );
    }

    private function addInformationTextField(FormInterface $form, mixed $choice): void
    {
        if ('yes' === $choice) {
            $form->add('suspectsText', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 300,
                    ]),
                ],
                'label' => 'pel.facts.suspects.informations.text',
            ]);
        }
    }
}
