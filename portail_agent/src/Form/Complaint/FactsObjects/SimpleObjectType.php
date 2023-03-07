<?php

declare(strict_types=1);

namespace App\Form\Complaint\FactsObjects;

use App\Entity\FactsObjects\SimpleObject;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleObjectType extends AbstractObjectType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('nature', TextType::class, [
                'disabled' => true,
                'label' => 'pel.object.label',
            ])
            ->add('description', TextType::class, [
                'disabled' => true,
                'label' => 'pel.object.description',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SimpleObject::class,
        ]);
    }
}
