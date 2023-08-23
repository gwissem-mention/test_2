<?php

declare(strict_types=1);

namespace App\Form\Objects;

use App\AppEnum\DeclarantStatus;
use App\Form\AddressEtalabType;
use App\Form\Model\Objects\DocumentAdditionalInformationModel;
use App\Form\PhoneType;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DocumentAdditionalInformationType extends AbstractType
{
    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly PhoneNumberUtil $phoneUtil,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('documentOwnerLastName', TextType::class, [
                'label' => 'pel.document.owner.lastname',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('documentOwnerFirstName', TextType::class, [
                'label' => 'pel.document.owner.firstname',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('documentOwnerPhone', PhoneType::class, [
                'required' => false,
                'label' => false,
                'priority' => -2,
                'number_label' => 'pel.document.owner.phone',
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
                            } catch (NumberParseException) {
                                $context->addViolation('pel.phone.is.invalid');
                            }
                        },
                    ]),
                ],
            ])
            ->add('documentOwnerEmail', EmailType::class, [
                'label' => 'pel.document.owner.email',
                'required' => false,
                'priority' => -2,
                'attr' => [
                    'maxlength' => 50,
                ],
                'constraints' => [
                    new Length(['max' => 50]),
                    new Email(),
                ],
            ])
            ->add('documentOwnerAddress', AddressEtalabType::class, [
                'label' => 'pel.document.owner.address',
                'required' => false,
                'priority' => -2,
            ])
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $this->addCompanyFields($event->getForm());
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DocumentAdditionalInformationModel::class,
        ]);
    }

    private function addCompanyFields(FormInterface $form): void
    {
        /** @var ?ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();

        if ($complaint?->getIdentity()?->getDeclarantStatus() === DeclarantStatus::CorporationLegalRepresentative->value) {
            $form
                ->add('documentOwnerCompany', TextType::class, [
                    'label' => 'pel.document.owner.company',
                    'required' => false,
                    'priority' => -1,
                ])
            ;
        }
    }
}
