<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Form\Model\Facts\OffenseDateModel;
use App\Session\SessionHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class OffenseDateType extends AbstractType
{
    private ?string $userTimezone;

    public function __construct(private readonly SessionHandler $sessionHandler)
    {
        $this->userTimezone = $this->sessionHandler->getComplaint()?->getTimezone();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('exactDateKnown', ChoiceType::class, [
                'label' => 'pel.complaint.exact.date.known',
                'expanded' => true,
                'multiple' => false,
                'inline' => true,
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('choiceHour', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => 'yes',
                    'pel.no' => 'no',
                    'pel.no.but.i.know.the.time.slot' => 'maybe',
                ],
                'expanded' => true,
                'inline' => true,
                'label' => 'pel.do.you.know.hour.facts',
                'constraints' => [
                    new NotNull(),
                ],
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var ?OffenseDateModel $offenseDateModel */
                $offenseDateModel = $event->getData();
                $form = $event->getForm();
                $this->addDateFields($form, $offenseDateModel?->isExactDateKnown());
                $this->addDateTimeHourField($form, $offenseDateModel?->getChoiceHour());
            }
        );

        $builder->get('exactDateKnown')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $exactDateKnown = $event->getForm()->getData();
                if (null === $exactDateKnown) {
                    return;
                }
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var ?OffenseDateModel $offenseDateModel */
                $offenseDateModel = $parent->getData();
                $this->addDateFields($parent, boolval($exactDateKnown), $offenseDateModel);
            }
        );

        $builder->get('choiceHour')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var ?string $choice */
                $choice = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var ?OffenseDateModel $offenseDateModel */
                $offenseDateModel = $parent->getData();
                $this->addDateTimeHourField($parent, $choice, $offenseDateModel);
            }
        );
    }

    private function addDateFields(
        FormInterface $form,
        bool $exactDateKnown = null,
        OffenseDateModel $offenseDateModel = null
    ): void {
        if (null === $exactDateKnown) {
            return;
        }

        $form->add('startDate', DateType::class, [
            'attr' => [
                'max' => date('Y-m-d'),
            ],
            'constraints' => [
                new NotBlank(),
                new LessThanOrEqual('today', message: 'pel.date.less.than.equal.today.error'),
            ],
            'label' => true === $exactDateKnown ? 'pel.complaint.exact.date' : 'pel.potential.start.date',
            'help' => 'pel.date.help',
            'widget' => 'single_text',
        ]);

        if (false === $exactDateKnown) {
            $form->add('endDate', DateType::class, [
                'attr' => [
                    'max' => date('Y-m-d'),
                ],
                'constraints' => [
                    new Callback([
                        'callback' => static function (?\DateTime $value, ExecutionContextInterface $context) {
                            if (null === $value) {
                                return;
                            }

                            if (!$value instanceof \DateTime) {
                                $context->addViolation('pel.end.date.is.invalid');
                            }

                            /** @var Form $form */
                            $form = $context->getObject();
                            /** @var Form $formParent */
                            $formParent = $form->getParent();
                            /** @var ?\DateTime $startDate */
                            $startDate = $formParent->get('startDate')->getData();

                            if ($value->getTimestamp() < $startDate?->getTimestamp()) {
                                $context->addViolation('pel.start.date.after.end.date');
                            } elseif ($value->getTimestamp() === $startDate?->getTimestamp()) {
                                $context->addViolation('pel.start.date.same.as.end.date');
                            }
                        },
                    ]),
                    new NotBlank(),
                    new LessThanOrEqual('today', message: 'pel.date.less.than.equal.today.error'),
                ],
                'label' => 'pel.potential.end.date',
                'help' => 'pel.date.help',
                'widget' => 'single_text',
            ]);
        } else {
            $form->remove('endDate');
            $offenseDateModel?->setEndDate(null);
        }
    }

    private function addDateTimeHourField(
        FormInterface $form,
        string $choice = null,
        OffenseDateModel $offenseDateModel = null
    ): void {
        if ('yes' === $choice) {
            $form
                ->remove('startHour')
                ->remove('endHour')
                ->add('hour', TimeType::class, [
                    'attr' => [
                        'class' => 'fr-input',
                    ],
                    'label' => 'pel.exact.hour',
                    'widget' => 'single_text',
                    'model_timezone' => 'UTC',
                    'view_timezone' => $this->userTimezone,
                    'reference_date' => new \DateTime('now', new \DateTimeZone('UTC')),
                    'constraints' => [
                        new Callback([
                            'callback' => static function (?\DateTime $value, ExecutionContextInterface $context) {
                                if (null === $value) {
                                    return;
                                }

                                if (!$value instanceof \DateTime) {
                                    $context->addViolation('pel.time.is.invalid');
                                }

                                /** @var Form $form */
                                $form = $context->getObject();
                                /** @var Form $formParent */
                                $formParent = $form->getParent();

                                if (!$formParent->has('endDate')) {
                                    /** @var \DateTime|null $startDate */
                                    $startDate = $formParent->get('startDate')->getData();
                                    /** @var \DateTime $now */
                                    $now = new \DateTime('now', new \DateTimeZone('UTC'));

                                    if (
                                        $startDate
                                        && $now->format('d-m-Y') === $startDate->format('d-m-Y')
                                        && time() < strtotime($value->format('H:i:s'))
                                    ) {
                                        $context->addViolation('pel.hour.after.now');
                                    }
                                }
                            },
                        ]),
                        new NotBlank(),
                    ],
                ]);

            $offenseDateModel?->setStartHour(null)->setEndHour(null);
        } elseif ('maybe' === $choice) {
            $form
                ->remove('hour')
                ->add('startHour', TimeType::class, [
                    'attr' => [
                        'class' => 'fr-input',
                    ],
                    'label' => 'pel.start.hour',
                    'widget' => 'single_text',
                    'model_timezone' => 'UTC',
                    'view_timezone' => $this->userTimezone,
                    'reference_date' => new \DateTime('now', new \DateTimeZone('UTC')),
                    'constraints' => [
                        new NotBlank(),
                        new Callback([
                            'callback' => static function (?\DateTime $value, ExecutionContextInterface $context) {
                                if (null === $value) {
                                    return;
                                }

                                if (!$value instanceof \DateTime) {
                                    $context->addViolation('pel.start.hour.is.invalid');
                                }

                                /** @var Form $form */
                                $form = $context->getObject();
                                /** @var Form $formParent */
                                $formParent = $form->getParent();

                                if (!$formParent->has('endDate')) {
                                    /** @var \DateTime|null $startDate */
                                    $startDate = $formParent->get('startDate')->getData();
                                    $now = new \DateTime('now', new \DateTimeZone('UTC'));

                                    if (
                                        $startDate
                                        && $now->format('d-m-Y') === $startDate->format('d-m-Y')
                                        && time() < strtotime($value->format('H:i:s'))
                                    ) {
                                        $context->addViolation('pel.hour.after.now');
                                    }
                                }
                            },
                        ],
                        ), ],
                ])
                ->add('endHour', TimeType::class, [
                    'attr' => [
                        'class' => 'fr-input',
                    ],
                    'model_timezone' => 'UTC',
                    'view_timezone' => $this->userTimezone,
                    'reference_date' => new \DateTime('now', new \DateTimeZone('UTC')),
                    'constraints' => [
                        new Callback([
                            'callback' => static function (?\DateTime $value, ExecutionContextInterface $context) {
                                if (null === $value) {
                                    return;
                                }

                                if (!$value instanceof \DateTime) {
                                    $context->addViolation('pel.end.hour.is.invalid');
                                }
                                /** @var Form $form */
                                $form = $context->getObject();
                                /** @var Form $formParent */
                                $formParent = $form->getParent();

                                if (null === $formParent->get('startHour')->getData()) {
                                    return;
                                }

                                if (!$formParent->has('endDate')) {
                                    /** @var \DateTime $startHour */
                                    $startHour = $formParent->get('startHour')->getData();
                                    /** @var \DateTime $startDate */
                                    $startDate = $formParent->get('startDate')->getData();
                                    $now = new \DateTime('now', new \DateTimeZone('UTC'));

                                    if ($value->getTimestamp() < $startHour->getTimestamp()) {
                                        $context->addViolation('pel.start.hour.after.end.hour');
                                    } elseif ($value->getTimestamp() === $startHour->getTimestamp()) {
                                        $context->addViolation('pel.start.hour.same.as.end.hour');
                                    } elseif ($now->format('d-m-Y') === $startDate->format('d-m-Y') && time() < strtotime($value->format('H:i:s'))) {
                                        $context->addViolation('pel.hour.after.now');
                                    }
                                }
                            },
                        ]),
                        new NotBlank(),
                    ],
                    'label' => 'pel.end.hour',
                    'widget' => 'single_text',
                ]);

            $offenseDateModel?->setHour(null);
        } else {
            $form
                ->remove('startHour')
                ->remove('endHour')
                ->remove('hour');

            $offenseDateModel?->setStartHour(null)->setEndHour(null)->setHour(null);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffenseDateModel::class,
        ]);
    }
}
