<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Entity\AdditionalInformation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdditionalInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var AdditionalInformation $additionalInformation */
        $additionalInformation = $options['data'];

        $builder
            ->add('suspectsKnown', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.do.you.have.informations.on.potential.suspects',
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'multiple' => false,
            ])
            ->add('witnessesPresent', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.facts.witnesses',
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'multiple' => false,
            ])
            ->add('fsiVisit', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.fsi.visit',
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'multiple' => false,
            ])
            ->add('cctvPresent', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => AdditionalInformation::CCTV_PRESENT_YES,
                    'pel.no' => AdditionalInformation::CCTV_PRESENT_NO,
                    'pel.i.dont.know' => AdditionalInformation::CCTV_PRESENT_DONT_KNOW,
                ],
                'expanded' => true,
                'label' => 'pel.cctv.present',
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'multiple' => false,
            ])
            ->add('victimOfViolence', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.victim.of.violence',
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'multiple' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'pel.description.of.facts',
            ]);

        if ($additionalInformation->isSuspectsKnown()) {
            $builder->add('suspectsKnownText', TextType::class, [
                'label' => 'pel.facts.suspects.informations.text',
            ]);
        }

        if ($additionalInformation->isWitnessesPresent()) {
            $builder->add('witnessesPresentText', TextType::class, [
                'label' => 'pel.facts.witnesses.information.text',
            ]);
        }

        if ($additionalInformation->isFsiVisit()) {
            $builder->add('observationMade', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.observation.made',
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'multiple' => false,
            ]);
        }

        if (AdditionalInformation::CCTV_PRESENT_YES === $additionalInformation->getCctvPresent()) {
            $builder->add('cctvAvailable', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.cctv.available',
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'multiple' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdditionalInformation::class,
            'disabled' => true,
        ]);
    }
}
