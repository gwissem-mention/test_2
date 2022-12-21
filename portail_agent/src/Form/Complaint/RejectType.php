<?php

namespace App\Form\Complaint;

use App\Entity\Complaint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RejectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('refusalReason', ChoiceType::class, [
                    'label' => 'pel.refusal.reason',
                    'choices' => [
                        'pel.appointment.needed' => Complaint::REFUSAL_REASON_REORIENTATION_APPONTMENT,
                        'pel.reorientation.other.solution' => Complaint::REFUSAL_REASON_REORIENTATION_OTHER_SOLUTION,
                        'pel.absence.of.penal.offense' => Complaint::REFUSAL_REASON_ABSENCE_PENAL_OFFENSE,
                        'pel.insufisant.quality.to.act' => Complaint::REFUSAL_REASON_INSUFISANT_QUALITY_TO_ACT,
                        'pel.victime.carence' => Complaint::REFUSAL_REASON_VICTIM_CARENCE,
                        'pel.other' => Complaint::REFUSAL_REASON_OTHER,
                    ],
                ]
            )
            ->add('refusalText', TextareaType::class, [
                'label' => 'pel.free.text',
                'required' => false,
                'attr' => [
                    'minlength' => 100,
                    'maxlength' => 5000,
                ],
                'constraints' => [
                    new Length(['min' => 100, 'max' => 5000]),
                    new Callback([
                        'callback' => static function (?string $value, ExecutionContextInterface $context) {
                            /** @var Form $form */
                            $form = $context->getObject();
                            /** @var Form $formParent */
                            $formParent = $form->getParent();
                            /** @var int $refusalReason */
                            $refusalReason = $formParent->get('refusalReason')->getData();

                            if (Complaint::REFUSAL_REASON_OTHER === $refusalReason && null === $value) {
                                $context->addViolation('This value should not be blank.');
                            }
                        },
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Complaint::class,
        ]);
    }
}
