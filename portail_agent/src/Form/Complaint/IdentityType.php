<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Entity\Identity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdentityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civility', ChoiceType::class, [
                'choices' => [
                    'pel.madam' => Identity::CIVILITY_FEMALE,
                    'pel.sir' => Identity::CIVILITY_MALE,
                ],
                'label' => false,
                'expanded' => true,
                'disabled' => true,
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'pel.lastname',
                'disabled' => true,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'pel.firstname',
                'disabled' => true,
            ])
            ->add('birthCountry', TextType::class, [
                'label' => 'pel.birth.country',
                'disabled' => true,
            ])
            ->add('birthday', DateType::class, [
                'label' => 'pel.birth.date',
                'disabled' => true,
                'widget' => 'single_text',
            ])
            ->add('birthCity', TextType::class, [
                'label' => 'pel.birth.city',
                'disabled' => true,
            ])
            ->add('mobilePhone', TextType::class, [
                'label' => 'pel.phone.number',
                'disabled' => true,
            ])
            ->add('email', TextType::class, [
                'label' => 'pel.email',
                'disabled' => true,
            ])
            ->add('address', TextType::class, [
                'label' => 'pel.address.france.label',
                'disabled' => true,
            ])
            ->add('declarantStatus', ChoiceType::class, [
                'choices' => [
                    'pel.victim' => Identity::DECLARANT_STATUS_VICTIM,
                    'pel.person.legal.representative' => Identity::DECLARANT_STATUS_PERSON_LEGAL_REPRESENTATIVE,
                    'pel.corporation.legal.representative' => Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE,
                ],
                'label' => 'pel.declarant.complain.as',
                'disabled' => true,
            ])
            ->add('smsNotifications', CheckboxType::class, [
                'label' => 'pel.want.to.receive.sms.notifications',
                'disabled' => true,
                'mapped' => false,
                'attr' => [
                    'checked' => $options['is_optin_notification'],
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Identity::class,
            'is_optin_notification' => false,
        ]);
    }
}
