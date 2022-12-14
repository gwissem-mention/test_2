<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressEtalabType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, [
                'label' => $options['address_label'],
                'constraints' => $options['address_constraints'],
                'data' => $options['address_data'],
            ])
            ->add('selectionId', HiddenType::class)
            ->add('query', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => false,
            'address_label' => 'pel.address',
            'address_constraints' => [],
            'address_data' => null,
            'mapped' => false,
        ]);
    }
}
