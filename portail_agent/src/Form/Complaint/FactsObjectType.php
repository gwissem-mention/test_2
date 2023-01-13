<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Entity\FactsObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactsObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
            ->add('operator', TextType::class, [
                'label' => 'pel.mobile.operator',
                'disabled' => true,
            ])
            ->add('imei', TextType::class, [
                'label' => 'pel.mobile.imei',
                'disabled' => true,
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'pel.phone.number.line',
                'disabled' => true,
            ])
            ->add('amount', TextType::class, [
                'label' => 'pel.object.estimated.amount',
                'disabled' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FactsObject::class,
        ]);
    }
}
