<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Form\Model\Facts\ObjectModel;
use App\Thesaurus\ObjectCategoryThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class ObjectType extends AbstractType
{
    /** @var array|mixed[] */
    private readonly array $objectCategories;

    public function __construct(
        private readonly ObjectCategoryThesaurusProviderInterface $objectCategoryThesaurusProvider
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
            ])
            ->add('label', TextType::class, [
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
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var ?ObjectModel $objectModel */
                $objectModel = $event->getData();
                $this->addCategoryFields($event->getForm(), $objectModel?->getCategory());
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
        ]);
    }

    private function addCategoryFields(FormInterface $form, ?int $category, ?ObjectModel $objectModel = null): void
    {
        $this->removeCategoryOtherFields($form, $objectModel);
        $this->removeCategoryMultimediaFields($form, $objectModel);
        $this->removeCategoryPaymentWaysFields($form, $objectModel);

        switch ($category) {
            case $this->objectCategories['pel.object.category.other']:
                $this->addCategoryOtherFields($form);
                break;
            case $this->objectCategories['pel.object.category.multimedia']:
                $this->addCategoryMultimediaFields($form);
                break;
            case $this->objectCategories['pel.object.category.payment.ways']:
                $this->addCategoryPaymentWaysFields($form);
                break;
        }
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

    private function addCategoryMultimediaFields(FormInterface $form): void
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
            ->add('phoneNumberLine', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 20,
                    ]),
                ],
                'label' => 'pel.phone.number.line',
                'required' => false,
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

    private function removeCategoryMultimediaFields(FormInterface $form, ?ObjectModel $objectModel): void
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
}
