<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Entity\Corporation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CorporationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sirenNumber', TextType::class, [
                'label' => 'pel.siren.number',
                'disabled' => true,
            ])
            ->add('companyName', TextType::class, [
                'label' => 'pel.company.name',
                'disabled' => true,
            ])
            ->add('declarantPosition', TextType::class, [
                'label' => 'pel.declarant.position',
                'disabled' => true,
            ])
            ->add('nationality', TextType::class, [
                'label' => 'pel.nationality',
                'disabled' => true,
            ])
            ->add('contactEmail', TextType::class, [
                'label' => 'pel.contact.email',
                'disabled' => true,
            ])
            ->add('phone', TextType::class, [
                'label' => 'pel.phone',
                'disabled' => true,
            ])
            ->add('country', TextType::class, [
                'label' => 'pel.country',
                'disabled' => true,
            ])
            ->add('address', TextType::class, [
                'label' => 'pel.address',
                'disabled' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Corporation::class,
        ]);
    }
}
