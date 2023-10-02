<?php

declare(strict_types=1);

namespace App\Form\AdditionalInformation;

use App\Form\Model\AdditionalInformation\WitnessModel;
use App\Form\PhoneType;
use App\Form\Validator\MobileValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class WitnessType extends AbstractType
{
    public function __construct(
        private readonly MobileValidator $mobileValidator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'autocomplete' => 'email',
                ],
                'constraints' => [
                    new Email(),
                ],
                'required' => false,
                'label' => 'pel.witness.email',
                'help' => 'pel.email.help',
            ])
            ->add('phone', PhoneType::class, [
                'required' => false,
                'label' => false,
                'number_label' => 'pel.mobile',
                'number_constraints' => [
                    new Callback([
                        'callback' => function (?string $value, ExecutionContextInterface $context) {
                            $this->mobileValidator->validate($value, $context);
                        },
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WitnessModel::class,
            'attr' => [
                'novalidate' => true,
            ],
        ]);
    }
}
