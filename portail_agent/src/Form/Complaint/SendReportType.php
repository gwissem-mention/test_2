<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Form\DropzoneType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SendReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('files', DropzoneType::class, [
                'label' => $options['is_after_appointment'] ? 'pel.upload.report.optional' : false,
                'accepted_files' => 'image/jpeg,image/png,application/pdf',
                'constraints' => $options['is_after_appointment'] ? [] : [new NotBlank()],
            ]);

        if ($options['is_after_appointment']) {
            $builder
                ->add('appointment_done', ChoiceType::class, [
                    'label' => 'pel.do.you.confirm.that.you.made.the.appointment',
                    'choices' => [
                        'pel.yes' => true,
                        'pel.no' => false,
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    'label_attr' => [
                        'class' => 'radio-inline',
                    ],
                    'attr' => [
                        'data-complaint-target' => 'appointmentDoneRadioButton',
                        'data-action' => 'change->complaint#isClosableAfterAppointment',
                    ],
                    'priority' => 1,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'is_after_appointment' => 'false',
        ]);
    }
}
