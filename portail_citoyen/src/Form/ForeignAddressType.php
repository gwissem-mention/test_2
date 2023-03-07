<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Model\Address\AddressForeignModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForeignAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('housenumber', TextType::class, [
                'label' => 'pel.address.number',
                'required' => false,
                'attr' => [
                    'disabled' => $options['fields_disabled'],
                ],
            ])
            ->add('type', TextType::class, [
                'label' => 'pel.address.type',
                'required' => false,
                'attr' => [
                    'disabled' => $options['fields_disabled'],
                ],
            ])
            ->add('street', TextType::class, [
                'label' => 'pel.address.name',
                'required' => false,
                'attr' => [
                    'disabled' => $options['fields_disabled'],
                ],
            ])
            ->add('apartment', TextType::class, [
               'label' => 'pel.address.apartment',
                'required' => false,
                'attr' => [
                    'disabled' => $options['fields_disabled'],
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'pel.address.city',
                'required' => false,
                'attr' => [
                    'disabled' => $options['fields_disabled'],
                ],
            ])
            ->add('context', TextType::class, [
                'label' => 'pel.address.context',
                'required' => false,
                'attr' => [
                    'disabled' => $options['fields_disabled'],
                ],
            ])
            ->add('postCode', TextType::class, [
                'label' => 'pel.address.postcode',
                'required' => false,
                'attr' => [
                    'disabled' => $options['fields_disabled'],
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddressForeignModel::class,
            'fields_disabled' => false,
        ]);
    }
}
