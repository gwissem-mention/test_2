<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\Enum\Civility;
use App\Form\LocationType;
use App\Form\Model\Identity\CivilStateModel;
use App\Session\SessionHandler;
use App\Thesaurus\JobThesaurusProviderInterface;
use App\Thesaurus\NationalityThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class CivilStateType extends AbstractType
{
    public function __construct(
        private readonly NationalityThesaurusProviderInterface $nationalityThesaurusProvider,
        private readonly JobThesaurusProviderInterface $jobThesaurusProvider,
        private readonly SessionHandler $sessionHandler
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
            ->add('job', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->jobThesaurusProvider->getChoices(),
                'label' => 'pel.your.job',
                'placeholder' => 'pel.your.job.choice.message',
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
