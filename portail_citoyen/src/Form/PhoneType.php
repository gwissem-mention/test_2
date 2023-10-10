<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Model\Identity\PhoneModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneType extends AbstractType
{
    private const FRANCE_CODE = 'FR';
    private const FRANCE_DIAL_CODE = '33';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TelType::class, [
                'attr' => [
                    'class' => 'phone-intl',
                    'data-intl-tel-input-target' => 'number',
                    'data-action' => 'input->intl-tel-input#trimByPattern',
                    'data-intl-tel-input-pattern-param' => '[^0-9-\s]',
                    'data-placeholder-type' => $options['number_placeholder_type'],
                    'autocomplete' => 'tel',
                ],
                'constraints' => $options['number_constraints'],
                'required' => $options['number_required'],
                'help' => $options['number_help'],
                'label' => $options['number_label'],
            ])
            ->add('code', HiddenType::class, [
                'attr' => [
                    'class' => 'phone-intl-dial-code',
                    'data-intl-tel-input-target' => 'code',
                ],
                'empty_data' => self::FRANCE_DIAL_CODE,
            ])
            ->add('country', HiddenType::class, [
                'attr' => [
                    'class' => 'phone-intl-country',
                    'data-intl-tel-input-target' => 'country',
                ],
                'empty_data' => self::FRANCE_CODE,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PhoneModel::class,
            'number_label' => 'pel.phone',
            'number_help' => 'pel.phone.help',
            'number_placeholder_type' => 'MOBILE',
            'number_required' => true,
            'number_constraints' => [],
        ]);
    }
}
