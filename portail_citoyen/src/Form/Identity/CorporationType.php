<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\Form\CountryAutocompleteType;
use App\Form\Model\Identity\CorporationModel;
use App\Form\NationalityAutocompleteType;
use App\Form\PhoneType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
    private const SIRET_NUMBER_LENGTH = 14;

    public function __construct(
        private readonly EventSubscriberInterface $addAddressSubscriber,
        private readonly string $franceCode,
        private readonly string $frenchNationalityCode,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('siret', TextType::class, [
                'attr' => [
                    'maxlength' => self::SIRET_NUMBER_LENGTH,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Regex('/\d{14}/', 'pel.only.integers.are.allowed'),
                    new Length(['min' => self::SIRET_NUMBER_LENGTH, 'max' => self::SIRET_NUMBER_LENGTH]),
                ],
                'label' => 'pel.corporation.siret',
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
            ->add('nationality', NationalityAutocompleteType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'aria-hidden' => 'true',
                    'data-live-id' => 'nationality-corporation-'.microtime(),
                    'data-controller' => 'tom-select-extend',
                ],
                'label' => 'pel.nationality',
                'preferred_choices' => [$this->frenchNationalityCode],
                'empty_data' => $this->frenchNationalityCode,
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
            ->add('country', CountryAutocompleteType::class, [
                'attr' => [
                    'aria-hidden' => 'true',
                    'data-controller' => 'tom-select-extend',
                ],
                'label' => 'pel.address.country',
                'preferred_choices' => [$this->franceCode],
                'empty_data' => $this->franceCode,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        if ($options['need_same_address_field']) {
            $builder->add('sameAddress', CheckboxType::class, [
                'label' => 'pel.same.address.as.declarant',
                'required' => false,
                'attr' => [
                    'data-controller' => 'form',
                    'data-action' => 'form#sameAddress',
                ],
            ]);
        }
        $builder->addEventSubscriber($this->addAddressSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CorporationModel::class,
            'need_same_address_field' => false,
        ]);
    }
}
