<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\Form\CountryAutocompleteType;
use App\Form\Model\Identity\ContactInformationModel;
use App\Form\Model\Identity\PhoneModel;
use App\Form\PhoneType;
use App\Form\Validator\MobileValidator;
use App\Session\SessionHandler;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ContactInformationType extends AbstractType
{
    public function __construct(
        private readonly EventSubscriberInterface $addAddressSubscriber,
        private readonly SessionHandler $sessionHandler,
        private readonly int $franceCode,
        private readonly PhoneNumberUtil $phoneUtil,
        private readonly MobileValidator $mobileValidator,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ?ContactInformationModel $contactInformationModel */
        $contactInformationModel = $this->sessionHandler->getComplaint()?->getIdentity()?->getContactInformation();

        $builder
            ->add('country', CountryAutocompleteType::class, [
                'attr' => [
                    'autocomplete' => 'country-name',
                    'data-live-id' => 'country-'.microtime(),
                    'aria-hidden' => 'true',
                    'data-controller' => 'tom-select-extend',
                ],
                'label' => 'pel.address.country',
                'preferred_choices' => [$this->franceCode],
                'empty_data' => $this->franceCode,
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'maxlength' => 254,
                    'autocomplete' => 'email',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 254]),
                    new Email(),
                ],
                'label' => 'pel.email.address',
                'help' => 'pel.email.help',
                'disabled' => $options['is_france_connected'] && $contactInformationModel?->getEmail(),
            ])
            ->add('mobile', PhoneType::class, [
                'required' => false,
                'label' => false,
                'number_label' => 'pel.mobile',
                'number_help' => 'pel.mobile.phone.help',
                'number_constraints' => [
                    new Callback([
                        'callback' => function (?string $value, ExecutionContextInterface $context) {
                            $this->mobileValidator->validate($value, $context);
                        },
                    ]),
                    new Callback([
                        'callback' => static function (?string $value, ExecutionContextInterface $context) {
                            /** @var Form $form */
                            $form = $context->getObject();
                            /** @var Form $formParent */
                            $formParent = $form->getParent()?->getParent();
                            /** @var ?PhoneModel $phone */
                            $phone = $formParent->get('phone')->getData();
                            if (null === $value && null === $phone?->getNumber()) {
                                $context->addViolation('pel.phone.or.mobile.error');
                            }
                        },
                    ]), ],
            ])
            ->add('phone', PhoneType::class, [
                'required' => false,
                'label' => false,
                'number_label' => 'pel.phone',
                'number_placeholder_type' => 'FIXED_LINE',
                'number_constraints' => [
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

                                if (false === in_array($this->phoneUtil->getNumberType($phone), [PhoneNumberType::FIXED_LINE, PhoneNumberType::TOLL_FREE, PhoneNumberType::VOIP], true)) {
                                    $context->addViolation('pel.phone.fixe.error');
                                }
                            } catch (NumberParseException) {
                                $context->addViolation('pel.phone.is.invalid');
                            }
                        },
                    ]),
                    new Callback([
                        'callback' => static function (?string $value, ExecutionContextInterface $context) {
                            /** @var Form $form */
                            $form = $context->getObject();
                            /** @var Form $formParent */
                            $formParent = $form->getParent()?->getParent();
                            /** @var ?PhoneModel $mobile */
                            $mobile = $formParent->get('mobile')->getData();
                            if (null === $value && null === $mobile?->getNumber()) {
                                $context->addViolation('pel.phone.or.mobile.error');
                            }
                        },
                    ]), ],
            ]);

        if (!$options['is_france_connected'] && !$contactInformationModel?->getEmail()) {
            $builder->add('confirmationEmail', EmailType::class, [
                'attr' => [
                    'maxlength' => 254,
                    'onpaste' => 'return false;',
                    'autocomplete' => 'email',
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 254]),
                    new Email(),
                    new Callback([$this, 'validateConfirmationEmail']),
                ],
                'label' => 'pel.email.confirmation',
            ]);
        }

        $builder->addEventSubscriber($this->addAddressSubscriber);
    }

    public function validateConfirmationEmail(?string $confirmationEmail, ExecutionContextInterface $context): void
    {
        /** @var Form $form */
        $form = $context->getObject();
        /** @var Form $formParent */
        $formParent = $form->getParent();
        /** @var string $email */
        $email = $formParent->get('email')->getData();

        if ($email !== $confirmationEmail) {
            $context->buildViolation('Veuillez saisir deux adresses emails identiques.')
                ->atPath('confirmationEmail')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactInformationModel::class,
            'need_same_address_field' => false,
            'is_france_connected' => false,
        ]);
    }
}
