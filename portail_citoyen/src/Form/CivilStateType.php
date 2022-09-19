<?php

declare(strict_types=1);

namespace App\Form;

use App\Enum\DeclarantStatus;
use App\Thesaurus\JobThesaurusProviderInterface;
use App\Thesaurus\NationalityThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class CivilStateType extends AbstractType
{
    public function __construct(
        private readonly NationalityThesaurusProviderInterface $nationalityThesaurusProvider,
        private readonly JobThesaurusProviderInterface $jobThesaurusProvider
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civility', ChoiceType::class, [
                'choices' => [
                    'pel.m' => 1,
                    'pel.mme' => 2,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'pel.civility',
                'placeholder' => 'pel.choose.your.civility',
            ])
            ->add('birthName', TextType::class, [
                'attr' => [
                    'data-controller' => 'form',
                    'data-action' => 'keyup->form#toUpperCase change->form#toUpperCase',
                    'maxlength' => 70,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 70]),
                ],
                'label' => 'pel.birth.name',
            ])
            ->add('firstnames', TextType::class, [
                'attr' => [
                    'maxlength' => 40,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 40]),
                ],
                'label' => 'pel.first.names',
            ])
            ->add('birthDate', DateType::class, [
                'constraints' => [
                    new NotBlank(),
                    new LessThanOrEqual('today'),
                ],
                'format' => 'dd/MM/yyyy',
                'help' => 'pel.birth.date.help',
                'html5' => false,
                'label' => 'pel.birth.date',
                'widget' => 'single_text',
            ])
            ->add('birthLocation', LocationType::class, [
                'country_label' => 'pel.birth.country',
                'town_label' => 'pel.birth.town',
                'department_label' => 'pel.birth.department',
            ])
            ->add('nationality', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->nationalityThesaurusProvider->getChoices(),
                'label' => 'pel.nationality',
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $this->addJobField($event->getForm());
            }
        );

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                /** @var array<string, mixed> $data */
                $data = $event->getData();
                $job = null;
                $jobNone = $this->jobThesaurusProvider->getChoices()['pel.job.none'];

                if ($data['job'] && !($jobNone === $data['job'])) {
                    $job = $data['job'];
                } elseif (DeclarantStatus::PersonLegalRepresentative->value === $event->getForm()->getConfig()
                        ->getOption('declarant_status')) {
                    $job = $jobNone;
                }

                $this->addJobField($event->getForm(), intval($job));
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'declarant_status' => null,
        ]);
    }

    private function addJobField(FormInterface $form, ?int $job = null): void
    {
        $form->add('job', ChoiceType::class, [
            'constraints' => [
                new NotBlank(),
            ],
            'choices' => $this->jobThesaurusProvider->getChoices(),
            'data' => $job,
            'label' => 'pel.your.job',
            'placeholder' => 'pel.your.job.choice.message',
        ]);
    }
}
