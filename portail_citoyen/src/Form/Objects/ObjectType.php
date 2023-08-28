<?php

declare(strict_types=1);

namespace App\Form\Objects;

use App\AppEnum\DeclarantStatus;
use App\AppEnum\MultimediaNature;
use App\AppEnum\PaymentCategory;
use App\AppEnum\RegisteredVehicleNature;
use App\Form\Model\Objects\ObjectModel;
use App\Form\PhoneType;
use App\Session\SessionHandler;
use App\Thesaurus\ObjectCategoryThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;

class ObjectType extends AbstractType
{
    private const FRANCE_CODE = 'FR';

    /** @var array|mixed[] */
    private readonly array $objectCategories;

    public function __construct(
        private readonly ObjectCategoryThesaurusProviderInterface $objectCategoryThesaurusProvider,
        private readonly SessionHandler $sessionHandler,
    ) {
        $this->objectCategories = $this->objectCategoryThesaurusProvider->getChoices();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', ChoiceType::class, [
                'choices' => $this->objectCategories,
                'label' => 'pel.object.category',
                'placeholder' => 'pel.object.category.choose',
                'constraints' => [
                    new NotBlank(),
                ],
                'priority' => 1000,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'pel.stolen' => ObjectModel::STATUS_STOLEN,
                    'pel.degraded' => ObjectModel::STATUS_DEGRADED,
                ],
                'label' => 'pel.object.status',
                'placeholder' => 'pel.object.status.choose',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('files', FileType::class, [
                'required' => false,
                'label' => 'pel.add.an.attachment',
                'help' => 'pel.object.files.help',
                'multiple' => true,
                'mapped' => false,
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var ?ObjectModel $objectModel */
                $objectModel = $event->getData();
                $form = $event->getForm();
                $this->addCategoryFields($form, $objectModel?->getCategory());

                if (ObjectModel::STATUS_STOLEN === $objectModel?->getStatus() && !empty($objectModel->getSerialNumber()) && $objectModel->getCategory() === $this->objectCategories['pel.object.category.mobile.phone']) {
                    $this->addAdditionalMobileFields($form);
                }

                if ($this->objectCategories['pel.object.category.registered.vehicle'] === $objectModel?->getCategory()) {
                    $this->addDegradationDescriptionField($form, ObjectModel::STATUS_DEGRADED === $objectModel->getStatus());
                }

                if ($this->objectCategories['pel.object.category.payment.ways'] === $objectModel?->getCategory() && PaymentCategory::Checkbook === $objectModel->getPaymentCategory()) {
                    $this->addCheckFields($form);
                }
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                /** @var array|mixed[] $data */
                $data = $event->getData();
                $form = $event->getForm();

                /** @var string $status */
                $status = $data['status'] ?? '';
                /** @var string $category */
                $category = $data['category'] ?? '';
                /** @var string $paymentCategory */
                $paymentCategory = $data['paymentCategory'] ?? '';

                if (!empty($status) && (string) ObjectModel::STATUS_STOLEN === $status && !empty($data['serialNumber']) && !empty($category) && (int) $category === $this->objectCategories['pel.object.category.mobile.phone']) {
                    $this->addAdditionalMobileFields($form);
                } else {
                    $this->removeAdditionalMobileFields($form);
                }

                if (!empty($status) && (string) ObjectModel::STATUS_DEGRADED === $status && !empty($category) && $this->objectCategories['pel.object.category.registered.vehicle'] === (int) $category) {
                    $this->addDegradationDescriptionField($form, true);
                } else {
                    $this->removeDegradationDescriptionField($form);
                }

                if (!empty($category) && $this->objectCategories['pel.object.category.payment.ways'] === (int) $category && PaymentCategory::Checkbook->value === (int) $paymentCategory) {
                    $this->addCheckFields($form);
                } else {
                    $this->removeCheckFields($form);
                }
            }
        );

        $builder->get('category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var int $category */
                $category = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var ?ObjectModel $objectModel */
                $objectModel = $parent->getData();

                $this->addCategoryFields($parent, intval($category), $objectModel);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ObjectModel::class,
            'attr' => [
                'novalidate' => true,
            ],
        ]);
    }

    private function addCategoryFields(FormInterface $form, int $category = null, ObjectModel $objectModel = null): void
    {
        $this->removeCategoryOtherFields($form, $objectModel);
        $this->removeCategoryMobilePhoneFields($form, $objectModel);
        $this->removeCategoryPaymentWaysFields($form, $objectModel);
        $this->removeCategoryRegisteredVehicleFields($form, $objectModel);
        $this->removeAmountField($form, $objectModel);
        $this->removeLabelField($form, $objectModel);
        $this->removeCategoryDocumentFields($form, $objectModel);
        $this->removeCategoryMultimediaFields($form, $objectModel);

        switch ($category) {
            case $this->objectCategories['pel.object.category.documents']:
                $this->addCategoryDocumentFields($form);
                break;
            case $this->objectCategories['pel.object.category.other']:
                $this->addCategoryOtherFields($form);
                $this->addAmountField($form, 'pel.amount.for.group');
                $this->addLabelField($form);
                break;
            case $this->objectCategories['pel.object.category.mobile.phone']:
                $this->addCategoryMobilePhoneFields($form);
                $this->addAmountField($form);
                break;
            case $this->objectCategories['pel.object.category.multimedia']:
                $this->addCategoryMultimediaFields($form);
                $this->addAmountField($form);
                break;
            case $this->objectCategories['pel.object.category.payment.ways']:
                $this->addCategoryPaymentWaysFields($form);
                $this->addLabelField($form);
                break;
            case $this->objectCategories['pel.object.category.registered.vehicle']:
                $this->addCategoryRegisteredVehicleFields($form);
                $this->addAmountField($form);
                break;
            default:
                $this->addAmountField($form);
                $this->addLabelField($form);
                break;
        }
    }

    private function addCategoryDocumentFields(FormInterface $form): void
    {
        $form->add('documentType', DocumentTypeType::class, [
            'compound' => true,
            'label' => false,
            'priority' => 999,
            'row_attr' => [
                'class' => 'fr-select-group',
            ],
        ]);
    }

    private function addCategoryOtherFields(FormInterface $form): void
    {
        $form
            ->add('description', TextType::class, [
                'attr' => [
                    'maxlength' => 200,
                ],
                'label' => 'pel.description',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 200,
                    ]),
                ],
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'pel.quantity',
                'html5' => true,
                'empty_data' => 1,
                'attr' => [
                    'min' => 1,
                    'class' => 'fr-col-3',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Positive(),
                ],
            ])
            ->add('serialNumber', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
                'label' => 'pel.other.serial.number',
                'help' => 'pel.this.number.is.required.to.identity.your.object',
                'required' => false,
            ]);
    }

    private function addCategoryMobilePhoneFields(FormInterface $form): void
    {
        $form
            ->add('brand', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 20,
                    ]),
                ],
                'label' => 'pel.brand',
            ])
            ->add('model', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
                'label' => 'pel.model',
                'required' => false,
            ])
            ->add('phoneNumberLine', PhoneType::class, [
                'label' => false,
                'number_label' => 'pel.phone.number.line',
                'number_required' => false,
            ])
            ->add('operator', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
                'label' => 'pel.operator',
                'required' => false,
            ])
            ->add('serialNumber', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
                'help' => 'pel.serial.number.help',
                'label' => 'pel.serial.number',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'min' => 0,
                        'max' => 255,
                    ]),
                ],
                'label' => 'pel.description',
                'required' => false,
                'attr' => [
                    'class' => 'fr-input',
                    'data-counter-target' => 'parent',
                    'minlength' => 0,
                    'maxlength' => 255,
                ],
            ]);
    }

    private function addCategoryPaymentWaysFields(FormInterface $form): void
    {
        $form
            ->add('paymentCategory', EnumType::class, [
                'class' => PaymentCategory::class,
                'choice_label' => fn (PaymentCategory $paymentCategory) => PaymentCategory::getLabel($paymentCategory->value),
                'label' => 'pel.payment.category',
                'placeholder' => 'pel.object.category.choose',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('bank', TextType::class, [
                'attr' => [
                    'maxlength' => 20,
                ],
                'label' => 'pel.organism.bank',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 20,
                    ]),
                ],
            ])
            ->add('bankAccountNumber', TextType::class, [
                'attr' => [
                    'maxlength' => 30,
                ],
                'label' => 'pel.bank.account.number',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 30,
                    ]),
                ],
            ])
            ->add('creditCardNumber', TextType::class, [
                'attr' => [
                    'maxlength' => 30,
                ],
                'required' => false,
                'label' => 'pel.credit.card.number',
                'constraints' => [
                    new Length([
                        'max' => 30,
                    ]),
                ],
            ]);
    }

    private function addDegradationDescriptionField(FormInterface $form, bool $required = false): void
    {
        $constraints = [
            new Length([
                'min' => 10,
                'max' => 1500,
            ]),
        ];

        if ($required) {
            $constraints[] = new NotBlank();
        }

        $form->add('degradationDescription', TextareaType::class, [
            'constraints' => $constraints,
            'required' => $required,
            'label' => 'pel.degradation.description',
            'attr' => [
                'data-counter-target' => 'parent',
                'minlength' => 10,
                'maxlength' => 1500,
            ],
        ]);
    }

    private function removeDegradationDescriptionField(FormInterface $form): void
    {
        $form->remove('degradationDescription');
    }

    private function addCategoryRegisteredVehicleFields(FormInterface $form): void
    {
        $form
            ->add('registeredVehicleNature', ChoiceType::class, [
                'choices' => RegisteredVehicleNature::getChoices(),
                'placeholder' => 'pel.choose.a.vehicle.category',
                'label' => 'pel.vehicle.category',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('brand', TextType::class, [
                'attr' => [
                    'maxlength' => 20,
                ],
                'label' => 'pel.brand',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 20,
                    ]),
                ],
            ])
            ->add('model', TextType::class, [
                'attr' => [
                    'maxlength' => 20,
                ],
                'required' => false,
                'label' => 'pel.model',
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
            ])
            ->add('registrationNumber', TextType::class, [
                'attr' => [
                    'maxlength' => 20,
                ],
                'required' => true,
                'label' => 'pel.registration.number',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 20,
                    ]),
                ],
            ])
            ->add('registrationNumberCountry', CountryType::class, [
                'empty_data' => self::FRANCE_CODE,
                'label' => 'pel.registration.number.country',
                'preferred_choices' => [self::FRANCE_CODE],
            ])
            ->add('insuranceCompany', TextType::class, [
                'attr' => [
                    'maxlength' => 20,
                ],
                'label' => 'pel.insurance.company',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
            ])
            ->add('insuranceNumber', TextType::class, [
                'attr' => [
                    'maxlength' => 20,
                ],
                'label' => 'pel.insurance.number',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
            ]);
    }

    private function addAmountField(FormInterface $form, string $label = 'pel.amount'): void
    {
        $form->add('amount', MoneyType::class, [
            'label' => $label,
            'scale' => 2,
            'currency' => false,
            'html5' => true,
            'required' => false,
            'constraints' => [
                new Length([
                    'min' => 1,
                    'max' => 12,
                ]),
            ],
        ]);
    }

    private function removeAmountField(FormInterface $form, ?ObjectModel $objectModel): void
    {
        $form->remove('amount');
        $objectModel?->setAmount(null);
    }

    private function removeCategoryMobilePhoneFields(FormInterface $form, ?ObjectModel $objectModel): void
    {
        $form
            ->remove('brand')
            ->remove('model')
            ->remove('phoneNumberLine')
            ->remove('operator')
            ->remove('serialNumber')
            ->remove('description');

        $objectModel
            ?->setBrand(null)
            ->setModel(null)
            ->setPhoneNumberLine(null)
            ->setOperator(null)
            ->setSerialNumber(null)
            ->setDescription(null);
    }

    private function removeCategoryOtherFields(FormInterface $form, ?ObjectModel $objectModel): void
    {
        $form
            ->remove('description')
            ->remove('quantity')
            ->remove('serialNumber');

        $objectModel?->setDescription(null)->setQuantity(null)->setSerialNumber(null);
    }

    private function removeCategoryPaymentWaysFields(FormInterface $form, ?ObjectModel $objectModel): void
    {
        $form
            ->remove('bank')
            ->remove('bankAccountNumber')
            ->remove('creditCardNumber')
            ->remove('paymentCategory');

        $objectModel?->setBank(null)->setBankAccountNumber(null)->setCreditCardNumber(null)->setPaymentCategory(null);
    }

    private function removeCategoryRegisteredVehicleFields(FormInterface $form, ?ObjectModel $objectModel): void
    {
        $form
            ->remove('brand')
            ->remove('registeredVehicleNature')
            ->remove('model')
            ->remove('registrationNumber')
            ->remove('registrationNumberCountry')
            ->remove('insuranceCompany')
            ->remove('insuranceNumber');

        $objectModel
            ?->setBrand(null)
            ->setModel(null)
            ->setRegistrationNumber(null)
            ->setRegistrationNumberCountry(null)
            ->setInsuranceCompany(null)
            ->setInsuranceNumber(null)
            ->setRegisteredVehicleNature(null);
    }

    private function removeCategoryDocumentFields(FormInterface $form, ?ObjectModel $objectModel): void
    {
        $form->remove('documentType');

        $objectModel?->setDocumentType(null);
    }

    private function addAdditionalMobileFields(FormInterface $form): void
    {
        $form
            ->add('stillOnWhenMobileStolen', ChoiceType::class, [
                'label' => 'pel.still.on.when.mobile.stolen',
                'expanded' => true,
                'multiple' => false,
                'inline' => true,
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('keyboardLockedWhenMobileStolen', ChoiceType::class, [
                'label' => 'pel.keyboard.locked.when.mobile.stolen',
                'expanded' => true,
                'multiple' => false,
                'inline' => true,
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('pinEnabledWhenMobileStolen', ChoiceType::class, [
                'label' => 'pel.pin.enabled.when.mobile.stolen',
                'expanded' => true,
                'multiple' => false,
                'inline' => true,
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('mobileInsured', ChoiceType::class, [
                'label' => 'pel.mobile.insured',
                'expanded' => true,
                'multiple' => false,
                'inline' => true,
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('allowOperatorCommunication', CheckboxType::class, [
                'required' => true,
                'label' => 'pel.i.am.inform.of.article.34',
                'constraints' => [
                    new IsTrue(),
                ],
            ]);
    }

    private function removeAdditionalMobileFields(FormInterface $form, ObjectModel $objectModel = null): void
    {
        $form
            ->remove('stillOnWhenMobileStolen')
            ->remove('keyboardLockedWhenMobileStolen')
            ->remove('pinEnabledWhenMobileStolen')
            ->remove('mobileInsured')
            ->remove('allowOperatorCommunication');

        $objectModel
            ?->setStillOnWhenMobileStolen(null)
            ->setKeyboardLockedWhenMobileStolen(null)
            ->setPinEnabledWhenMobileStolen(null)
            ->setMobileInsured(null)
            ->setAllowOperatorCommunication(null);
    }

    private function addCategoryMultimediaFields(FormInterface $form): void
    {
        $form
            ->add('multimediaNature', ChoiceType::class, [
                'choices' => MultimediaNature::getChoices(),
                'placeholder' => 'pel.choose.a.multimedia.nature',
                'label' => 'pel.multimedia.nature',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('brand', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 20,
                    ]),
                ],
                'label' => 'pel.brand',
            ])
            ->add('model', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
                'label' => 'pel.model',
                'required' => false,
            ])
            ->add('serialNumber', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
                'label' => 'pel.serial.number',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'min' => 0,
                        'max' => 255,
                    ]),
                ],
                'label' => 'pel.description',
                'required' => false,
                'attr' => [
                    'class' => 'fr-input',
                    'data-counter-target' => 'parent',
                    'minlength' => 0,
                    'maxlength' => 255,
                ],
            ]);

        if (DeclarantStatus::CorporationLegalRepresentative->value === $this->sessionHandler->getComplaint()?->getIdentity()?->getDeclarantStatus()) {
            $this->addOwnerInformation($form);
        }
    }

    private function removeCategoryMultimediaFields(FormInterface $form, ?ObjectModel $objectModel): void
    {
        $form
            ->remove('multimediaNature')
            ->remove('brand')
            ->remove('model')
            ->remove('serialNumber')
            ->remove('description');

        $objectModel
            ?->setMultimediaNature(null)
            ->setBrand(null)
            ->setModel(null)
            ->setSerialNumber(null)
            ->setDescription(null);

        $this->removeOwnerInformation($form, $objectModel);
    }

    private function addOwnerInformation(FormInterface $form): void
    {
        $form
            ->add('ownerFirstname', TextType::class, [
                'label' => 'pel.owner.firstname',
                'empty_data' => $this->sessionHandler->getComplaint()?->getIdentity()?->getCivilState()->getFirstnames(),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('ownerLastname', TextType::class, [
                'label' => 'pel.owner.lastname',
                'empty_data' => $this->sessionHandler->getComplaint()?->getIdentity()?->getCivilState()->getBirthName(),
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    private function removeOwnerInformation(FormInterface $form, ObjectModel $objectModel = null): void
    {
        $form
            ->remove('ownerLastname')
            ->remove('ownerFirstname');

        $objectModel
            ?->setOwnerLastname(null)
            ->setOwnerFirstname(null);
    }

    private function addCheckFields(FormInterface $form): void
    {
        $form
            ->add('checkNumber', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
                'required' => false,
                'label' => 'pel.check.number',
            ])
            ->add('checkFirstNumber', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
                'required' => false,
                'label' => 'pel.first.check.number',
            ])
            ->add('checkLastNumber', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
                'required' => false,
                'label' => 'pel.last.check.number',
            ]);
    }

    private function removeCheckFields(FormInterface $form): void
    {
        $form
            ->remove('checkNumber')
            ->remove('checkFirstNumber')
            ->remove('checkLastNumber');
    }

    private function addLabelField(FormInterface $form): void
    {
        $form->add('label', TextType::class, [
            'attr' => [
                'maxlength' => 30,
            ],
            'label' => false,
            'constraints' => [
                new NotBlank(),
                new Length([
                    'max' => 30,
                ]),
            ],
            'priority' => 999,
        ]);
    }

    private function removeLabelField(FormInterface $form, ?ObjectModel $objectModel): void
    {
        $form->remove('label');
        $objectModel?->setLabel(null);
    }
}
