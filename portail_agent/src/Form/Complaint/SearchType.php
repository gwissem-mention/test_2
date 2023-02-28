<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', \Symfony\Component\Form\Extension\Core\Type\SearchType::class, [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'pel.search.your.declarations',
                    ],
                    'required' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'role' => 'search',
                'data-action' => 'datatable#search:prevent',
            ],
            'method' => 'GET',
        ]);
    }
}
