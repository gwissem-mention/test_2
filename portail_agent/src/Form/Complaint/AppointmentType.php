<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Entity\Complaint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('appointmentContactInformation', TextareaType::class, [
                'disabled' => true,
                'label' => 'pel.information.entered.by.the.victim.to.make.an.appointment',
                'attr' => ['class' => 'textarea-resize-none'],
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var FormInterface $form */
                    $form = $event->getForm();
                    /** @var Complaint $complaint */
                    $complaint = $event->getData();

                    $this->addAppointmentDateField($form, null !== $complaint->getAppointmentDate());
                }
            )
        ;
    }

    private function addAppointmentDateField(FormInterface $form, bool $disabled): void
    {
        $form->add('appointmentDate', DateType::class, [
            'disabled' => $disabled,
            'input' => 'datetime_immutable',
            'widget' => 'single_text',
            'view_timezone' => 'UTC',
            'label' => false,
            'constraints' => [
                new NotBlank(),
                new GreaterThanOrEqual('today', message: 'pel.date.greater.than.equal.today.error'),
            ],
        ])
        ->add('appointmentTime', TimeType::class, [
            'disabled' => $disabled,
            'input' => 'datetime_immutable',
            'widget' => 'single_text',
            'label' => false,
            'constraints' => [
                new NotBlank(),
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
