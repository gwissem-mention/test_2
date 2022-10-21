<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\Thesaurus\NationalityThesaurusProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
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
        private readonly EventSubscriberInterface $addAddressSubscriber,
        private readonly NationalityThesaurusProviderInterface $nationalityThesaurusProvider,
        private readonly string $franceCode
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $nationalityChoices = $this->nationalityThesaurusProvider->getChoices();
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
                'label' => 'pel.corporation.siren',
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'maxlength' => 40,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 40]),
                ],
                'label' => 'pel.corporation.name',
            ])
            ->add('function', TextType::class, [
                'attr' => [
                    'maxlength' => 30,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 30]),
                ],
                'label' => 'pel.corporation.function',
            ])
            ->add('nationality', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $nationalityChoices,
                'label' => 'pel.corporation.nationality',
                'empty_data' => $nationalityChoices['pel.nationality.france'],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'maxlength' => 50,
                ],
                'constraints' => [
                    new Length(['max' => 50]),
                    new Email(),
                ],
                'label' => 'pel.corporation.email',
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
                'label' => 'pel.corporation.phone',
            ])
            ->add('country', CountryType::class, [
                'label' => 'pel.address.country',
                'preferred_choices' => [$this->franceCode],
                'empty_data' => $this->franceCode,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        $builder->get('country')->addEventSubscriber($this->addAddressSubscriber);
    }
}
