<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Form\OffenseDateType;
use App\Form\OffenseNatureType;
use App\Form\PlaceNatureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('offenseNature', OffenseNatureType::class, [
                'label' => false,
            ])
            ->add('placeNature', PlaceNatureType::class, [
                'label' => false,
            ])
            ->add('offenseDate', OffenseDateType::class, [
                'label' => false,
            ]);
    }
}
