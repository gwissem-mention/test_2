<?php

namespace App\Form;

use App\Form\Model\Identity\PhoneModel;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PhoneType extends AbstractType
{
    private const FRANCE_CODE = 'FR';
    private const FRANCE_DIAL_CODE = '33';

    public function __construct(private readonly PhoneNumberUtil $phoneUtil)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TelType::class, [
                'attr' => [
                    'class' => 'phone-intl',
                    'data-controller' => 'form',
                    'data-action' => 'input->form#trimByPattern',
                    'data-form-pattern-param' => '[^0-9-\s]',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Callback([
                        'callback' => function (?string $value, ExecutionContextInterface $context) {
                            if (null === $value) {
                                return;
                            }

                            /** @var Form $form */
                            $form = $context->getObject();
                            /** @var Form $formParent */
                            $formParent = $form->getParent();
                            /** @var string $country */
                            $country = $formParent->get('country')->getData();
                            try {
                                $phone = $this->phoneUtil->parse($value, $country);
                                if (!$this->phoneUtil->isValidNumber($phone)) {
                                    $context->addViolation('pel.phone.is.invalid');
                                }
                            } catch (NumberParseException) {
                                $context->addViolation('pel.phone.is.invalid');
                            }
                        },
                    ]),
                ],
                'help' => $options['number_help'],
                'label' => $options['number_label'],
            ])
            ->add('code', HiddenType::class, [
                'attr' => [
                    'class' => 'phone-intl-dial-code',
                ],
                'empty_data' => self::FRANCE_DIAL_CODE,
            ])
            ->add('country', HiddenType::class, [
                'attr' => [
                    'class' => 'phone-intl-country',
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
        ]);
    }
}
