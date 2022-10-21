<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Thesaurus\ObjectCategoryThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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

        $builder->get('category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var int $category */
                $category = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addCategoryFields($parent, intval($category));
            }
        );
    }

    private function addCategoryFields(FormInterface $form, int $category): void
    {
        switch ($category) {
            case $this->objectCategories['pel.object.category.other']:
                $this->addCategoryOtherFields($form);
                break;
            case $this->objectCategories['pel.object.category.multimedia']:
                $this->addCategoryMultimediaFields($form);
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
}
