<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Form\Model\Facts\FactsModel;
use App\Form\Model\Facts\ObjectModel;
use App\Session\SessionHandler;
use App\Thesaurus\NaturePlaceThesaurusProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        private readonly SessionHandler $sessionHandler,
        private readonly NaturePlaceThesaurusProviderInterface $naturePlaceThesaurusProvider,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('offenseNature', OffenseNatureType::class, [
                'label' => false,
            ])
            ->add('victimOfViolence', CheckboxType::class, [
                'label' => 'pel.victim.of.violence',
                'required' => false,
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
                'constraints' => [
                    new NotBlank(),
                ],
                'data' => $this->sessionHandler->getComplaint()?->getFacts()?->getObjects() ?: new ArrayCollection(
                    [new ObjectModel()]
                ),
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
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var FactsModel $factsModel */
                    $factsModel = $event->getData();
                    $form = $event->getForm();
                    $this->addAmountKnownField($form, $factsModel->isAmountKnown());
                    $this->addVictimOfViolenceField($form, $factsModel->isVictimOfViolence());
                }
            );

        $builder->get('amountKnown')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var bool $choice */
                $choice = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var FactsModel $factsModel */
                $factsModel = $parent->getData();
                $this->addAmountKnownField($parent, boolval($choice), $factsModel);
            }
        );

        $builder->get('victimOfViolence')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var bool $victimOfViolence */
                $victimOfViolence = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var FactsModel $factsModel */
                $factsModel = $parent->getData();
                $this->addVictimOfViolenceField($parent, boolval($victimOfViolence), $factsModel);
            }
        );
    }

    private function addVictimOfViolenceField(
        FormInterface $form,
        ?bool $victimOfViolence,
        ?FactsModel $factsModel = null
    ): void {
        if (true === $victimOfViolence) {
            $form->add('victimOfViolenceText', TextType::class, [
                'label' => 'pel.victim.of.violence.text',
                'attr' => [
                    'maxlength' => 250,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 250,
                    ]),
                ],
            ]);
        } else {
            $form->remove('victimOfViolenceText');
            $factsModel?->setVictimOfViolenceText(null);
        }
    }

    private function addAmountKnownField(FormInterface $form, ?bool $choice, ?FactsModel $factsModel = null): void
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
        } else {
            $form->remove('amount');
            $factsModel?->setAmount(null);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FactsModel::class,
        ]);
    }
}
