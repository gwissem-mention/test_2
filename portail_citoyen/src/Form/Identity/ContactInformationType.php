<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\Form\CountryType;
use App\Form\Model\Identity\ContactInformationModel;
use App\Form\Model\Identity\PhoneModel;
use App\Form\PhoneType;
use App\Session\SessionHandler;
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
        private readonly int $franceCode
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ?ContactInformationModel $contactInformationModel */
        $contactInformationModel = $this->sessionHandler->getComplaint()?->getIdentity()?->getContactInformation();

        $builder
            ->add('country', CountryType::class, [
                'label' => 'pel.address.country',
                'preferred_choices' => [$this->franceCode],
                'empty_data' => $this->franceCode,
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
                'disabled' => $options['is_france_connected'] && $contactInformationModel?->getEmail(),
            ])
            ->add('mobile', PhoneType::class, [
                'required' => false,
                'label' => false,
                'number_label' => 'pel.mobile',
                'number_constraints' => [new Callback([
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
                ])],
            ])
            ->add('phone', PhoneType::class, [
                'required' => false,
                'label' => false,
                'number_label' => 'pel.phone',
                'number_constraints' => [new Callback([
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
                ])],
            ]);

        $builder->addEventSubscriber($this->addAddressSubscriber);
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
