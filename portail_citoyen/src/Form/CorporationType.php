<?php

declare(strict_types=1);

namespace App\Form;

use App\Thesaurus\NationalityThesaurusProviderInterface;
use App\Thesaurus\WayThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CorporationType extends AbstractType
{
    public function __construct(
        private readonly NationalityThesaurusProviderInterface $nationalityThesaurusProvider,
        private readonly WayThesaurusProviderInterface $wayThesaurusProvider,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('siren', TextType::class, [
                'attr' => [
                    'maxlength' => 9,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Regex('/\d/', 'corporation.siren.number.error'),
                    new Length(['min' => 9, 'max' => 9]),
                ],
                'label' => 'corporation.siren',
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'maxlength' => 40,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 40]),
                ],
                'label' => 'corporation.name',
            ])
            ->add('function', TextType::class, [
                'attr' => [
                    'maxlength' => 30,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 30]),
                ],
                'label' => 'corporation.function',
            ])
            ->add('nationality', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->nationalityThesaurusProvider->getChoices(),
                'label' => 'corporation.nationality',
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'maxlength' => 50,
                ],
                'constraints' => [
                    new Length(['max' => 50]),
                    new Email(),
                ],
                'label' => 'corporation.email',
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'attr' => [
                    'maxlength' => 15,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 15]),
                ],
                'label' => 'corporation.phone',
            ])
            ->add('addressLocation', LocationType::class, [
                'country_label' => 'corporation.address.country',
                'town_label' => 'corporation.address.town',
                'department_label' => 'corporation.address.department',
            ])
            ->add('addressNumber', TextType::class, [
                'attr' => [
                    'maxlength' => 11,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 11]),
                ],
                'label' => 'corporation.address.number',
            ])
            ->add('addressWay', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->wayThesaurusProvider->getChoices(),
                'label' => 'corporation.address.way',
            ]);
    }
}
