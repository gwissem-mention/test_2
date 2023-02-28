<?php

namespace App\Form\Complaint\FactsObjects;

use App\Entity\FactsObjects\PaymentMethod;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentMethodType extends AbstractObjectType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('type', TextType::class, [
                'disabled' => true,
                'label' => 'pel.object.label',
            ])
            ->add('description', TextType::class, [
                'disabled' => true,
                'label' => 'pel.object.description',
            ])
            ->add('bank', TextType::class, [
                'disabled' => true,
                'label' => 'pel.object.bank',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentMethod::class,
        ]);
    }
}
