<?php

declare(strict_types=1);

namespace App\Form\Objects;

use App\Form\Model\Objects\ObjectModel;
use App\Form\PhoneType;
use App\Thesaurus\ObjectCategoryThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
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
                $this->addCategoryFields($event->getForm(), $objectModel?->getCategory());

                if (ObjectModel::STATUS_STOLEN === $objectModel?->getStatus() && !empty($objectModel->getSerialNumber())) {
                    $this->addAdditionalMobileFields($event->getForm());
                }
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                /** @var array|mixed[] $data */
                $data = $event->getData();

                if (!empty($data['status']) && (string) ObjectModel::STATUS_STOLEN === $data['status'] && !empty($data['serialNumber'])) {
                    $this->addAdditionalMobileFields($event->getForm());
                } else {
                    $this->removeAdditionalMobileFields($event->getForm());
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
                $this->addLabelField($form);
                break;
            case $this->objectCategories['pel.object.category.payment.ways']:
                $this->addCategoryPaymentWaysFields($form);
                $this->addLabelField($form);
                break;
            case $this->objectCategories['pel.object.category.registered.vehicle']:
                $this->addCategoryRegisteredVehicleFields($form);
                $this->addAmountField($form);
                $this->addLabelField($form);
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

    private function addCategoryRegisteredVehicleFields(FormInterface $form): void
    {
        $form
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
                'required' => false,
                'label' => 'pel.registration.number',
                'constraints' => [
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

    private function removeCategoryMobilePhoneFields(FormInterface $form, ?ObjectModel $objectModel): void
    {
        $form
            ->remove('brand')
            ->remove('model')
            ->remove('phoneNumberLine')
            ->remove('operator')
            ->remove('serialNumber');

        $objectModel
            ?->setBrand(null)
            ->setModel(null)
            ->setPhoneNumberLine(null)
            ->setOperator(null)
            ->setSerialNumber(null);
    }

    private function removeCategoryOtherFields(FormInterface $form, ?ObjectModel $objectModel): void
    {
        $form
            ->remove('description')
            ->remove('quantity');

        $objectModel?->setDescription(null)->setQuantity(null);
    }

    private function removeCategoryPaymentWaysFields(FormInterface $form, ?ObjectModel $objectModel): void
    {
        $form
            ->remove('bank')
            ->remove('bankAccountNumber')
            ->remove('creditCardNumber');

        $objectModel?->setBank(null)->setBankAccountNumber(null)->setCreditCardNumber(null);
    }

    private function removeCategoryRegisteredVehicleFields(FormInterface $form, ?ObjectModel $objectModel): void
    {
        $form
            ->remove('brand')
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
            ->setInsuranceNumber(null);
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
}
