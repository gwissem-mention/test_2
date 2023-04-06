<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\Enum\DeclarantStatus;
use App\Form\Model\Identity\DeclarantStatusModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DeclarantStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('declarantStatus', ChoiceType::class, [
                'label' => 'pel.complaint.identity.declarant.status',
                'expanded' => true,
                'multiple' => false,
                'rich' => true,
                'choices' => DeclarantStatus::getChoices(),
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DeclarantStatusModel::class,
            'attr' => [
                'novalidate' => true,
            ],
        ]);
    }
}
