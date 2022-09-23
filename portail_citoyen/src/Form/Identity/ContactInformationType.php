<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\Form\LocationType;
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
        $wayChoices = $this->wayThesaurusProvider->getChoices();

        $builder
            ->add('addressLocation', LocationType::class, [
                'country_label' => 'pel.address.country',
                'town_label' => 'pel.address.town',
                'department_label' => 'pel.address.department',
            ])
            ->add('addressNumber', TextType::class, [
                'attr' => [
                    'maxlength' => 11,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 11]),
                ],
                'label' => 'pel.address.number',
            ])
            ->add('addressWay', ChoiceType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $wayChoices,
                'empty_data' => $wayChoices['pel.way.one'],
                'label' => 'pel.address.way',
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
                'label' => 'pel.email',
            ])
            ->add('mobile', TextType::class, [
                'attr' => [
                    'maxlength' => 15,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 15]),
                ],
                'label' => 'pel.mobile',
            ]);
    }
}
