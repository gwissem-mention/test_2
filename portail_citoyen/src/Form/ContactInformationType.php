<?php

declare(strict_types=1);

namespace App\Form;

use App\Thesaurus\WayThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactInformationType extends AbstractType
{
    public function __construct(
        private readonly WayThesaurusProviderInterface $wayThesaurusProvider,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addressLocation', LocationType::class, [
                'country_label' => 'address.country',
                'town_label' => 'address.town',
                'department_label' => 'address.department',
            ])
            ->add('addressNumber', TextType::class, [
                'attr' => [
                    'maxlength' => 11,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 11]),
                ],
                'label' => 'address.number',
            ])
            ->add('addressWay', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $this->wayThesaurusProvider->getChoices(),
                'label' => 'address.way',
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'maxlength' => 50,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 50]),
                    new Email(),
                ],
                'label' => 'email',
            ])
            ->add('mobile', TextType::class, [
                'attr' => [
                    'maxlength' => 15,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 15]),
                ],
                'label' => 'mobile',
            ]);
    }
}
