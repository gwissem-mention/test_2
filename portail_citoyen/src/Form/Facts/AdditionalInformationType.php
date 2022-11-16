<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Form\Model\Facts\AdditionalInformationModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
                    'pel.yes' => AdditionalInformationModel::CCTV_PRESENT_YES,
                    'pel.no' => AdditionalInformationModel::CCTV_PRESENT_NO,
                    'pel.i.dont.know' => AdditionalInformationModel::CCTV_PRESENT_DONT_KNOW,
                ],
                'expanded' => true,
                'label' => 'pel.cctv.present',
                'multiple' => false,
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var ?AdditionalInformationModel $additionalInfo */
                $additionalInfo = $event->getData();
                $form = $event->getForm();
                $this->addInformationTextField($form, $additionalInfo?->isSuspectsChoice());
                $this->addWitnessesTextField($form, $additionalInfo?->isWitnesses());
                $this->addFSIVisitField($form, $additionalInfo?->isFsiVisit());
                $this->addCCTVAvailableField($form, $additionalInfo?->getCctvPresent());
                $this->addFSIVisitField($form, $additionalInfo?->isFsiVisit());
            }
        );

        $builder->get('suspectsChoice')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $choice = $event->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var ?AdditionalInformationModel $additionalInformationModel */
                $additionalInformationModel = $parent->getData();
                $this->addInformationTextField($parent, boolval($choice), $additionalInformationModel);
            }
        );

        $builder->get('witnesses')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $choice = $event->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var ?AdditionalInformationModel $additionalInformationModel */
                $additionalInformationModel = $parent->getData();
                $this->addWitnessesTextField($parent, boolval($choice), $additionalInformationModel);
            }
        );

        $builder->get('fsiVisit')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $choice = $event->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var ?AdditionalInformationModel $additionalInformationModel */
                $additionalInformationModel = $parent->getData();
                $this->addFSIVisitField($parent, boolval($choice), $additionalInformationModel);
            }
        );

        $builder->get('cctvPresent')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $choice = $event->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var ?AdditionalInformationModel $additionalInformationModel */
                $additionalInformationModel = $parent->getData();
                $this->addCCTVAvailableField($parent, intval($choice), $additionalInformationModel);
            }
        );
    }

    private function addInformationTextField(
        FormInterface $form,
        ?bool $choice,
        ?AdditionalInformationModel $additionalInformationModel = null
    ): void {
        if (true === $choice) {
            $form->add('suspectsText', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 300,
                    ]),
                ],
                'label' => 'pel.facts.suspects.informations.text',
            ]);
        } else {
            $form->remove('suspectsText');
            $additionalInformationModel?->setSuspectsText(null);
        }
    }

    private function addWitnessesTextField(
        FormInterface $form,
        ?bool $choice,
        ?AdditionalInformationModel $additionalInformationModel = null
    ): void {
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
        } else {
            $form->remove('witnessesText');
            $additionalInformationModel?->setWitnessesText(null);
        }
    }

    private function addFSIVisitField(
        FormInterface $form,
        ?bool $choice,
        ?AdditionalInformationModel $additionalInformationModel = null
    ): void {
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
        } else {
            $form->remove('observationMade');
            $additionalInformationModel?->setObservationMade(null);
        }
    }

    private function addCCTVAvailableField(
        FormInterface $form,
        ?int $choice,
        ?AdditionalInformationModel $additionalInformationModel = null
    ): void {
        if (AdditionalInformationModel::CCTV_PRESENT_YES === $choice) {
            $form->add('cctvAvailable', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.cctv.available',
                'multiple' => false,
            ]);
        } else {
            $form->remove('cctvAvailable');
            $additionalInformationModel?->setCctvAvailable(null);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdditionalInformationModel::class,
        ]);
    }
}
