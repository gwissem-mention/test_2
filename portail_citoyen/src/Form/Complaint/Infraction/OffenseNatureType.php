<?php

declare(strict_types=1);

namespace App\Form\Complaint\Infraction;

use App\Enum\OffenseNature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Length;

class OffenseNatureType extends AbstractType
{
    private const OTHER_AAB_TEXT_MAX_LENGTH = 800;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('offenseNature', ChoiceType::class, [
                'placeholder' => 'complaint.nature.of.the.facts',
                'label' => 'complaint.nature.of.the.facts',
                'choices' => OffenseNature::getChoices(),
            ])
            ->get('offenseNature')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onOffenseNaturePostSubmit']
            );
    }

    public function onOffenseNaturePostSubmit(FormEvent $event): void
    {
        if (OffenseNature::Other->value === intval($event->getData())) {
            $event->getForm()->getParent()?->add('aabText', TextareaType::class, [
                'label' => 'complaint.offense.nature.other.aab.text',
                'attr' => [
                    'maxlength' => self::OTHER_AAB_TEXT_MAX_LENGTH,
                ],
                'constraints' => [new Length(['max' => self::OTHER_AAB_TEXT_MAX_LENGTH])],
            ]);
        }
    }
}
