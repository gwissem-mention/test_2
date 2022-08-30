<?php

declare(strict_types=1);

namespace App\Form;

use App\Thesaurus\JobThesaurusProviderInterface;
use App\Thesaurus\NationalityThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
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
                    'm' => 1,
                    'mme' => 2,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'civility',
                'placeholder' => 'choose.your.civility',
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
                'label' => 'birth.name',
            ])
            ->add('firstnames', TextType::class, [
                'attr' => [
                    'maxlength' => 40,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 40]),
                ],
                'label' => 'first.names',
            ])
            ->add('birthDate', DateType::class, [
                'constraints' => [
                    new NotBlank(),
                    new LessThanOrEqual('today'),
                ],
                'format' => 'dd/MM/yyyy',
                'help' => 'birth.date.help',
                'html5' => false,
                'label' => 'birth.date',
                'widget' => 'single_text',
            ])
            ->add('birthLocation', LocationType::class, [
                'country_label' => 'birth.country',
                'town_label' => 'birth.town',
                'department_label' => 'birth.department',
            ])
            ->add('nationality', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->nationalityThesaurusProvider->getChoices(),
                'label' => 'nationality',
            ])
            ->add('job', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->jobThesaurusProvider->getChoices(),
                'label' => 'your.job',
                'placeholder' => 'your.job.placeholder',
            ]);
    }
}
