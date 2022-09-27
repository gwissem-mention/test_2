<?php

declare(strict_types=1);

namespace App\Form\Facts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdditionalInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('suspectsChoice', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.do.you.have.informations.on.potential.suspects',
                'multiple' => false,
            ])
            ->add('witnesses', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.facts.witnesses',
                'multiple' => false,
            ]);

        $builder->get('suspectsChoice')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $choice = $event->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addInformationTextField($parent, boolval($choice));
            }
        );

        $builder->get('witnesses')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $choice = $event->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addWitnessesTextField($parent, boolval($choice));
            }
        );
    }

    private function addInformationTextField(FormInterface $form, bool $choice): void
    {
        if (true === $choice) {
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

    private function addWitnessesTextField(FormInterface $form, bool $choice): void
    {
        if (true === $choice) {
            $form->add('witnessesText', TextType::class, [
                'attr' => [
                    'maxlength' => 400,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 400,
                    ]),
                ],
                'label' => 'pel.facts.witnesses.information.text',
            ]);
        }
    }
}
