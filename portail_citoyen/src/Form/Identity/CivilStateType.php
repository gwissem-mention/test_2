<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\Enum\Civility;
use App\Enum\DeclarantStatus;
use App\Form\LocationType;
use App\Form\Model\Identity\CivilStateModel;
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
        /** @var ?CivilStateModel $emptyData */
        $emptyData = $options['empty_data'];
        $nationalityChoices = $this->nationalityThesaurusProvider->getChoices();

        $builder
            ->add('civility', ChoiceType::class, [
                'choices' => Civility::getChoices(),
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'pel.civility',
                'placeholder' => 'pel.choose.your.civility',
                'empty_data' => $emptyData instanceof CivilStateModel ? $emptyData->getCivility() : null,
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
                'empty_data' => $emptyData instanceof CivilStateModel ? $emptyData->getBirthName() : null,
            ])
            ->add('usageName', TextType::class, [
                'attr' => [
                    'data-controller' => 'form',
                    'data-action' => 'keyup->form#toUpperCase change->form#toUpperCase',
                    'maxlength' => 70,
                ],
                'constraints' => [
                    new Length(['max' => 70]),
                ],
                'label' => 'pel.usage.name',
                'required' => false,
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
                'empty_data' => $emptyData instanceof CivilStateModel ? $emptyData->getFirstnames() : null,
            ])
            ->add('birthDate', DateType::class, [
                'constraints' => $options['birthDate_constraints'],
                'help' => 'pel.birth.date.help',
                'label' => 'pel.birth.date',
                'widget' => 'single_text',
                'empty_data' => $emptyData instanceof CivilStateModel ? $emptyData->getBirthDate()?->format('Y-m-d') :
                    null,
            ])
            ->add('birthLocation', LocationType::class, [
                'country_label' => 'pel.birth.country',
                'town_label' => 'pel.birth.town',
                'department_label' => 'pel.birth.department',
                'empty_data' => $emptyData instanceof CivilStateModel ? $emptyData->getBirthLocation() : null,
            ])
            ->add('nationality', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $nationalityChoices,
                'label' => 'pel.nationality',
                'empty_data' => $nationalityChoices['pel.nationality.france'],
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
                /** @var ?CivilStateModel $data */
                $data = $event->getData();
                $job = null;
                $jobNone = $this->jobThesaurusProvider->getChoices()['pel.job.none'];

                if ($data?->getJob() && !($jobNone === $data->getJob())) {
                    $job = $data->getJob();
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
        $resolver
            ->setDefaults([
                'data_class' => CivilStateModel::class,
                'declarant_status' => null,
                'birthDate_constraints' => [new NotBlank(), new LessThanOrEqual('today')],
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
