<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Form\DataTransformer\ObjectTransformer;
use App\Form\Model\Facts\FactsModel;
use App\Thesaurus\NaturePlaceThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class FactsType extends AbstractType
{
    public function __construct(
        private readonly ObjectTransformer $objectTransformer,
        private readonly NaturePlaceThesaurusProviderInterface $naturePlaceThesaurusProvider,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('offenseNature', OffenseNatureType::class, [
                'label' => false,
            ])
            ->add('placeNature', ChoiceType::class, [
                'choices' => $this->naturePlaceThesaurusProvider->getChoices(),
                'label' => 'pel.nature.place',
                'placeholder' => 'pel.nature.place.choose',
                'required' => false,
            ])
            ->add('address', AddressType::class, [
                'label' => false,
                'compound' => true,
            ])
            ->add('offenseDate', OffenseDateType::class, [
                'label' => false,
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'max' => 800,
                    ]),
                ],
                'label' => 'pel.facts.description.precise',
            ])
            ->add('objects', LiveCollectionType::class, [
                'entry_type' => ObjectType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'pel.objects',
                'label_attr' => [
                    'class' => 'fr-h6',
                ],
                'button_add_options' => [
                    'label' => 'pel.objects.add',
                    'attr' => [
                        'class' => 'fr-btn fr-btn--secondary',
                    ],
                ],
                'button_delete_options' => [
                    'label' => 'pel.delete',
                    'attr' => [
                        'class' => 'fr-btn fr-btn--tertiary-no-outline',
                    ],
                ],
                'data' => [[]],
                'constraints' => [
                    new NotBlank(),
                ],
                'mapped' => false,
            ])
            ->add('amountKnown', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.is.amount.known',
                'multiple' => false,
            ])
            ->add('additionalInformation', AdditionalInformationType::class, [
                'label' => false,
            ]);

        $builder->get('objects')->addModelTransformer($this->objectTransformer);

        $builder->get('amountKnown')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var bool $choice */
                $choice = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                $this->addAmountKnownField($parent, boolval($choice));
            }
        );
    }

    private function addAmountKnownField(FormInterface $form, bool $choice): void
    {
        if (true === $choice) {
            $form->add('amount', MoneyType::class, [
                'label' => 'pel.amount',
                'scale' => 0,
                'currency' => false,
                'html5' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 999999999999,
                ],
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => 12,
                    ]),
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FactsModel::class,
        ]);
    }
}
