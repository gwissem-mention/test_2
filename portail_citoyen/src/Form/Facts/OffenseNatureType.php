<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Enum\OffenseNature;
use App\Form\Model\Facts\OffenseNatureModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class OffenseNatureType extends AbstractType
{
    private const OTHER_AAB_TEXT_MAX_LENGTH = 800;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('offenseNature', ChoiceType::class, [
                'placeholder' => 'pel.complaint.nature.of.the.facts',
                'label' => 'pel.complaint.nature.of.the.facts',
                'choices' => OffenseNature::getChoices(),
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var ?OffenseNatureModel $offenseNature */
                    $offenseNature = $event->getData();
                    $this->onOffenseNaturePostSubmit($event->getForm(), $offenseNature?->getOffenseNature());
                }
            )
            ->get('offenseNature')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    /** @var FormInterface $parent */
                    $parent = $event->getForm()->getParent();
                    $this->onOffenseNaturePostSubmit($parent, intval($event->getForm()->getData()));
                }
            );
    }

    public function onOffenseNaturePostSubmit(FormInterface $form, ?int $offenseNature): void
    {
        if (OffenseNature::Other->value === $offenseNature) {
            $form->add('aabText', TextareaType::class, [
                'label' => 'pel.complaint.offense.nature.other.aab.text',
                'attr' => [
                    'maxlength' => self::OTHER_AAB_TEXT_MAX_LENGTH,
                ],
                'constraints' => [new Length(['max' => self::OTHER_AAB_TEXT_MAX_LENGTH])],
            ]);
        } else {
            $form->remove('aabText');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffenseNatureModel::class,
        ]);
    }
}
