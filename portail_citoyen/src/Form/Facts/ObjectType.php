<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Thesaurus\ObjectCategoryThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ObjectType extends AbstractType
{
    public function __construct(private readonly ObjectCategoryThesaurusProviderInterface $objectCategoryThesaurusProvider)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', ChoiceType::class, [
                'choices' => $this->objectCategoryThesaurusProvider->getChoices(),
                'label' => 'pel.object.category',
                'placeholder' => 'pel.object.category.choose',
            ])
            ->add('label', TextType::class, [
                'attr' => [
                    'maxlength' => 30,
                ],
                'label' => false,
                'constraints' => [
                    new Length([
                        'max' => 30,
                    ]),
                ],
            ])
        ;

        $builder->get('category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var ?int $choice */
                $choice = $event->getForm()->getData();
                if (is_null($choice)) {
                    return;
                }
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addMultimediaCategoryObjectForm($parent, $choice);
            }
        );
    }

    private function addMultimediaCategoryObjectForm(FormInterface $form, int $choice): void
    {
        $choices = $this->objectCategoryThesaurusProvider->getChoices();

        if ($choices['pel.object.category.multimedia'] === $choice) {
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
                ])
            ;
        }
    }
}
