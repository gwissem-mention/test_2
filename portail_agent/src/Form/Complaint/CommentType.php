<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => false,
                'disabled' => $options['field_disabled'],
                'attr' => [
                    'placeholder' => 'pel.write.your.comment.here',
                    'maxlength' => 2000,
                ],
                'constraints' => [
                    new Length([
                        'max' => 2000,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'field_disabled' => false,
        ]);
    }
}
