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
    private const CCTV_AVAILABLE_YES = 1;
    private const CCTV_AVAILABLE_NO = 2;
    private const CCTV_AVAILABLE_DONT_KNOW = 3;

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
            ])
            ->add('fsiVisit', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.fsi.visit',
                'multiple' => false,
            ])
            ->add('cctvPresent', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => self::CCTV_AVAILABLE_YES,
                    'pel.no' => self::CCTV_AVAILABLE_NO,
                    'pel.i.dont.know' => self::CCTV_AVAILABLE_DONT_KNOW,
                ],
                'expanded' => true,
                'label' => 'pel.cctv.present',
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

        $builder->get('fsiVisit')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $choice = $event->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addFSIVisitField($parent, boolval($choice));
            }
        );

        $builder->get('cctvPresent')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $choice = $event->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addCCTVAvailableField($parent, intval($choice));
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

    private function addFSIVisitField(FormInterface $form, bool $choice): void
    {
        if (true === $choice) {
            $form->add('observationMade', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.observation.made',
                'multiple' => false,
            ]);
        }
    }

    private function addCCTVAvailableField(FormInterface $form, int $choice): void
    {
        if (self::CCTV_AVAILABLE_YES === $choice) {
            $form->add('cctvAvailable', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.cctv.available',
                'multiple' => false,
            ]);
        }
    }
}
