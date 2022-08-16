<?php

declare(strict_types=1);

namespace App\Form;

use App\Thesaurus\JobThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    public function __construct(private readonly JobThesaurusProviderInterface $jobThesaurusProvider)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('job', ChoiceType::class, [
                'attr' => [
                    'class' => 'fr-input',
                ],
                'autocomplete' => true,
                'choices' => $this->jobThesaurusProvider->getChoices(),
                'label' => 'your.job',
                'label_attr' => [
                    'class' => 'fr-label',
                ],
                'placeholder' => 'your.job.placeholder',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
