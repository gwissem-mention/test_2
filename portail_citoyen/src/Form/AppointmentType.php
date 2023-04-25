<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Model\AppointmentModel;
use App\Session\ComplaintHandler;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AppointmentType extends AbstractType
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
            ->add('appointmentContactText', TextareaType::class, [
                'label' => 'pel.appointment.text.label',
                'required' => $required,
                'constraints' => $required ? new NotBlank() : [],
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
