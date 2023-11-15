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
    private const APPOINTMENT_VIDEOCONFERENCE = 0;
    private const APPOINTMENT_ON_SITE = 1;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('files', DropzoneType::class, [
                'label' => $options['has_scheduled_appointment'] ? 'pel.upload.report.optional' : false,
                'accepted_files' => 'image/jpeg,image/png,application/pdf',
                'constraints' => $options['has_scheduled_appointment'] ? [] : [new NotBlank()],
            ]);

        if ($options['has_scheduled_appointment']) {
            $builder
                ->add('appointment_done', ChoiceType::class, [
                    'label' => false,
                    'choices' => [
                        'pel.the.appointment.took.place.in.videoconference' => self::APPOINTMENT_VIDEOCONFERENCE,
                        'pel.the.appointment.took.place.on.site' => self::APPOINTMENT_ON_SITE,
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    'label_attr' => [
                        'class' => 'radio-inline',
                    ],
                    'attr' => [
                        'data-complaint-target' => 'appointmentDoneRadioButton',
                    ],
                    'priority' => 1,
                    'constraints' => [
                        new NotBlank(null, 'pel.you.must.choose.a.situation'),
                    ],
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'has_scheduled_appointment' => false,
        ]);
    }
}
