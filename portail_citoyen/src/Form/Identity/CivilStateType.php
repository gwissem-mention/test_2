<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\AppEnum\Civility;
use App\AppEnum\FamilySituation;
use App\Form\JobAutocompleteType;
use App\Form\LocationType;
use App\Form\Model\Identity\CivilStateModel;
use App\Form\NationalityAutocompleteType;
use App\Referential\Entity\Job;
use App\Referential\Repository\JobRepository;
use App\Session\SessionHandler;
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
        private readonly string $frenchNationalityCode,
        private readonly SessionHandler $sessionHandler,
        private readonly JobRepository $jobRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ?CivilStateModel $civilStateModel */
        $civilStateModel = $this->sessionHandler->getComplaint()?->getIdentity()?->getCivilState();

        $builder
            ->add('civility', ChoiceType::class, [
                'choices' => Civility::getChoices(),
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'pel.civility',
                'expanded' => true,
                'multiple' => false,
                'rich' => false,
                'inline' => true,
                'disabled' => $options['is_france_connected'] && $civilStateModel?->civilityIsDefined(),
            ])
            ->add('birthName', TextType::class, [
                'attr' => [
                    'data-controller' => 'form',
                    'data-action' => 'keyup->form#toUpperCase change->form#toUpperCase blur->form#dispatchEventChange',
                    'maxlength' => 70,
                    'autocomplete' => 'family-name',
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
                    'autocomplete' => 'given-name',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 40]),
                ],
                'label' => 'pel.first.names',
                'disabled' => $options['is_france_connected'] && $civilStateModel?->firstnamesIsDefined(),
            ])
            ->add('familySituation', ChoiceType::class, [
                'label' => 'pel.family.situation',
                'rich' => true,
                'choices' => FamilySituation::getChoices(),
                'placeholder' => 'pel.choose.a.family.situation',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('birthDate', DateType::class, [
                'attr' => [
                    'autocomplete' => 'bday',
                ],
                'constraints' => $options['birthDate_constraints'],
                'help' => 'pel.birth.date.help',
                'label' => 'pel.birth.date',
                'widget' => 'single_text',
                'disabled' => $options['is_france_connected'] && $civilStateModel?->birthDateIsDefined(),
            ])
            ->add('birthLocation', LocationType::class, [
                'country_label' => 'pel.birth.country',
                'town_label' => 'pel.birth.town',
                'disabled' => $options['is_france_connected'] && $civilStateModel?->birthLocationIsDefined(),
            ])
            ->add('nationality', NationalityAutocompleteType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'pel.nationality',
                'data' => $this->frenchNationalityCode,
                'placeholder' => '',
                'attr' => [
                    'data-live-id' => 'nationality-'.microtime(),
                    'aria-hidden' => 'true',
                    'data-controller' => 'tom-select-extend',
                ],
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var ?CivilStateModel $civilState */
                    $civilState = $event->getData();
                    $this->addJobField($event->getForm(), $civilState?->getJob());
                }
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    /** @var array<string, int|string> $civilState */
                    $civilState = $event->getData();
                    $this->addJobField(
                        $event->getForm(),
                        !empty($civilState['job']) ? strval($civilState['job']) : null,
                    );
                }
            );
    }

    private function addJobField(FormInterface $form, string $jobCode = null): void
    {
        $choices = [];
        $job = null;
        if (null !== $jobCode) {
            /** @var ?Job $job */
            $job = $this->jobRepository->findOneBy(['code' => $jobCode]);
            if (null !== $job) {
                $choices[$job->getLabel()] = $job->getCode();
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
                'placeholder' => 'pel.your.job.placeholder',
                'autocomplete_url' => $this->urlGenerator->generate(
                    'ux_entity_autocomplete',
                    ['alias' => 'job']
                ),
                'attr' => [
                    'data-controller' => 'tom-select-extend',
                    'data-load-text' => $this->translator->trans('pel.results.loading'),
                    'data-live-id' => 'job-'.microtime(),
                    'class' => 'job',
                    'data-url-civility-'.Civility::M->value => $this->urlGenerator->generate(
                        'ux_entity_autocomplete',
                        ['alias' => 'job']
                    ),
                    'aria-hidden' => 'true',
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
