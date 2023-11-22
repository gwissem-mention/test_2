<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Entity\Complaint;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AppointmentType extends AbstractType
{
    public function __construct(private readonly Security $security)
    {
    }

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

    // https://symfony.com/doc/current/reference/forms/types/date.html#rendering-a-single-html5-text-box
    private function addAppointmentDateField(FormInterface $form, bool $disabled): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $form->add('appointmentDate', DateType::class, [
            'attr' => [
                'disabled' => $disabled,
                'class' => 'js-datepicker d-none',
            ],
            'input' => 'datetime_immutable',
            'widget' => 'single_text',
            // prevents rendering it as type="date", to avoid HTML5 date pickers
            'html5' => false,
            'view_timezone' => 'UTC',
            'label' => false,
            'constraints' => [
                new NotBlank(),
                new GreaterThanOrEqual('today', message: 'pel.date.greater.than.equal.today.error'),
            ],
        ])
        ->add('appointmentTime', TimeType::class, [
            'input' => 'datetime_immutable',
            'widget' => 'single_text',
            'view_timezone' => $user->getTimezone(),
            'model_timezone' => 'UTC',
            'label' => false,
            'constraints' => [
                new NotBlank(),
                new Callback([
                    'callback' => static function (?\DateTimeImmutable $value, ExecutionContextInterface $context) {
                        if (null === $value) {
                            return;
                        }

                        if (!$value instanceof \DateTimeImmutable) {
                            $context->addViolation('pel.time.is.invalid');
                        }

                        /** @var Form $form */
                        $form = $context->getObject();
                        /** @var Form $formParent */
                        $formParent = $form->getParent();

                        /** @var \DateTimeImmutable|null $appointmentDate */
                        $appointmentDate = $formParent->get('appointmentDate')->getData();
                        /** @var \DateTimeImmutable $now */
                        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

                        if (
                            $appointmentDate
                            && $now->format('d-m-Y') === $appointmentDate->format('d-m-Y')
                            && time() > strtotime($value->format('H:i:s'))
                        ) {
                            $context->addViolation('pel.hour.before.now');
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
