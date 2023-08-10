<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Model\AppointmentModel;
use App\Session\ComplaintHandler;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class SummaryType extends AbstractType
{
    public function __construct(private readonly SessionHandler $sessionHandler, private readonly ComplaintHandler $complaintHandler)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();
        $required = $this->complaintHandler->isAppointmentRequired($complaint);

        $builder
            ->add('appointmentAsked', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.do.you.want.to.make.an.appointment.with.an.agent',
                'multiple' => false,
                'inline' => true,
                'constraints' => false === $required ? new NotNull() : [],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppointmentModel::class,
            'attr' => [
                'novalidate' => true,
            ],
        ]);
    }
}
