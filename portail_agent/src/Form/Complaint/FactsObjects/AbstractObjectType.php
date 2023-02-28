<?php

declare(strict_types=1);

namespace App\Form\Complaint\FactsObjects;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', TextType::class, [
                'label' => 'pel.object.estimated.amount',
                'disabled' => true,
            ]);
    }
}
