<?php

declare(strict_types=1);

namespace App\Form\Complaint\FactsObjects;

use App\Entity\FactsObjects\Vehicle;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehicleType extends AbstractObjectType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('label', TextType::class, [
                'label' => 'pel.object.label',
                'disabled' => 'true',
            ])
            ->add('brand', TextType::class, [
                'label' => 'pel.object.brand',
                'disabled' => true,
            ])
            ->add('model', TextType::class, [
                'label' => 'pel.object.model',
                'disabled' => true,
            ])
            ->add('registrationNumber', TextType::class, [
                'label' => 'pel.vehicle.registration.number',
                'disabled' => true,
            ])
            ->add('registrationCountry', TextType::class, [
                'label' => 'pel.vehicle.registration.country',
                'disabled' => true,
            ])
            ->add('insuranceCompany', TextType::class, [
                'label' => 'pel.vehicle.insurance.company',
                'disabled' => true,
            ])
            ->add('insuranceNumber', TextType::class, [
                'label' => 'pel.vehicle.insurance.number',
                'disabled' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
