<?php

declare(strict_types=1);

namespace App\Form\Complaint\FactsObjects;

use App\Entity\FactsObjects\AdministrativeDocument;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdministrativeDocumentType extends AbstractObjectType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('type', TextType::class, [
                'label' => 'pel.object.label',
                'disabled' => 'true',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdministrativeDocument::class,
        ]);
    }
}
