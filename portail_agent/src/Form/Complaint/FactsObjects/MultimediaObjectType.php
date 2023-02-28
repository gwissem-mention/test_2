<?php

declare(strict_types=1);

namespace App\Form\Complaint\FactsObjects;

use App\Entity\FactsObjects\MultimediaObject;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultimediaObjectType extends AbstractObjectType
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
            ->add('operator', TextType::class, [
                'label' => 'pel.mobile.operator',
                'disabled' => true,
            ])
            ->add('serialNumber', TextType::class, [
                'label' => 'pel.mobile.imei',
                'disabled' => true,
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'pel.phone.number.line',
                'disabled' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MultimediaObject::class,
        ]);
    }
}
