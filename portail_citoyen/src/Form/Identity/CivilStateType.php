<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\AppEnum\Civility;
use App\Form\JobAutocompleteType;
use App\Form\LocationType;
use App\Form\Model\Identity\CivilStateModel;
use App\Referential\Entity\Job;
use App\Referential\Repository\JobRepository;
use App\Session\SessionHandler;
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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class CivilStateType extends AbstractType
{
    public function __construct(
        private readonly NationalityThesaurusProviderInterface $nationalityThesaurusProvider,
        private readonly SessionHandler $sessionHandler,
        private readonly JobRepository $jobRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $nationalityChoices = $this->nationalityThesaurusProvider->getChoices();

        /** @var ?CivilStateModel $civilStateModel */
        $civilStateModel = $this->sessionHandler->getComplaint()?->getIdentity()?->getCivilState();

        $builder
            ->add('civility', ChoiceType::class, [
                'choices' => Civility::getChoices(),
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'pel.civility',
                'placeholder' => 'pel.choose.your.civility',
                'disabled' => $options['is_france_connected'] && $civilStateModel?->civilityIsDefined(),
                'attr' => [
                    'data-controller' => 'form',
                    'data-action' => 'change->form#changeJobUrl',
                ],
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
                'disabled' => $options['is_france_connected'] && $civilStateModel?->birthNameIsDefined(),
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
                'disabled' => $options['is_france_connected'] && $civilStateModel?->usageNameIsDefined(),
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
                'disabled' => $options['is_france_connected'] && $civilStateModel?->firstnamesIsDefined(),
            ])
            ->add('birthDate', DateType::class, [
                'constraints' => $options['birthDate_constraints'],
                'help' => 'pel.birth.date.help',
                'label' => 'pel.birth.date',
                'widget' => 'single_text',
                'disabled' => $options['is_france_connected'] && $civilStateModel?->birthDateIsDefined(),
            ])
            ->add('birthLocation', LocationType::class, [
                'country_label' => 'pel.birth.country',
                'town_label' => 'pel.birth.town',
                'department_label' => 'pel.birth.department',
                'disabled' => $options['is_france_connected'] && $civilStateModel?->birthLocationIsDefined(),
            ])
            ->add('nationality', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $nationalityChoices,
                'label' => 'pel.nationality',
                'empty_data' => $nationalityChoices['pel.nationality.france'],
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var ?CivilStateModel $civilState */
                    $civilState = $event->getData();
                    $this->addJobField($event->getForm(), $civilState?->getCivility(), $civilState?->getJob());
                }
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    /** @var array<string, int|string> $civilState */
                    $civilState = $event->getData();
                    $this->addJobField(
                        $event->getForm(),
                        !empty($civilState['civility']) ? intval($civilState['civility']) : null,
                        !empty($civilState['job']) ? strval($civilState['job']) : null,
                    );
                }
            );
    }

    private function addJobField(FormInterface $form, int $civility = null, string $jobCode = null): void
    {
        $choices = [];
        $job = null;
        if (!is_null($jobCode)) {
            /** @var ?Job $job */
            $job = $this->jobRepository->findOneBy(['code' => $jobCode]);
            if (!is_null($job)) {
                $choices[Civility::Mme->value === $civility ? $job->getLabelFemale() : $job->getLabelMale()] = $job->getCode();
            }
        }

        $form
            ->add('job', JobAutocompleteType::class, [
                'data' => $job?->getCode(),
                'choices' => $choices,
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'pel.your.job',
                'autocomplete_url' => $this->urlGenerator->generate(
                    'ux_entity_autocomplete',
                    ['alias' => Civility::M->value === $civility ? 'job_male' : 'job_female']
                ),
                'attr' => [
                    'required' => true,
                    'data-controller' => 'autocomplete',
                    'data-load-text' => $this->translator->trans('pel.results.loading'),
                    'class' => 'job',
                    'data-url-civility-'.Civility::M->value => $this->urlGenerator->generate(
                        'ux_entity_autocomplete',
                        ['alias' => 'job_male']
                    ),
                    'data-url-civility-'.Civility::Mme->value => $this->urlGenerator->generate(
                        'ux_entity_autocomplete',
                        ['alias' => 'job_female']
                    ),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => CivilStateModel::class,
                'birthDate_constraints' => [new NotBlank(), new LessThanOrEqual('today')],
                'is_france_connected' => false,
            ]);
    }
}
