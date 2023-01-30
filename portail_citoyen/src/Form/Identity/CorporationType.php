<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\Form\CountryType;
use App\Form\Model\Identity\CorporationModel;
use App\Form\PhoneType;
use App\Thesaurus\NationalityThesaurusProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CorporationType extends AbstractType
{
    public function __construct(
        private readonly EventSubscriberInterface $addAddressSubscriber,
        private readonly NationalityThesaurusProviderInterface $nationalityThesaurusProvider,
        private readonly EventSubscriberInterface $addAddressCountrySubscriber,
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
                    new Regex('/\d/', 'pel.only.integers.are.allowed'),
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
                'label' => 'pel.nationality',
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
            ->add('phone', PhoneType::class, [
                'label' => false,
                'number_label' => 'pel.corporation.phone',
                'number_constraints' => [new NotBlank()],
            ])
            ->add('country', CountryType::class, [
                'label' => 'pel.address.country',
                'preferred_choices' => [$this->franceCode],
                'empty_data' => $this->franceCode,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        $builder->addEventSubscriber($this->addAddressSubscriber);
        $builder->get('country')->addEventSubscriber($this->addAddressCountrySubscriber);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CorporationModel::class,
            'need_same_address_field' => false,
        ]);
    }
}
