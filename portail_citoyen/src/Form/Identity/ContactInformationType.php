<?php

declare(strict_types=1);

namespace App\Form\Identity;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactInformationType extends AbstractType
{
    public function __construct(
        private readonly EventSubscriberInterface $addAddressSubscriber,
        private readonly string $franceCode
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', CountryType::class, [
                'label' => 'pel.address.country',
                'preferred_choices' => [$this->franceCode],
                'empty_data' => $this->franceCode,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'maxlength' => 50,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 50]),
                    new Email(),
                ],
                'label' => 'pel.email',
            ])
            ->add('mobile', TextType::class, [
                'attr' => [
                    'maxlength' => 15,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 15]),
                ],
                'label' => 'pel.mobile',
            ]);

        $builder->get('country')->addEventSubscriber($this->addAddressSubscriber);
    }
}
