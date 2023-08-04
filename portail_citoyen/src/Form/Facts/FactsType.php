<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Form\Model\Facts\FactsModel;
use App\Thesaurus\NaturePlaceThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class FactsType extends AbstractType
{
    public function __construct(
        private readonly NaturePlaceThesaurusProviderInterface $naturePlaceThesaurusProvider,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'min' => 30,
                        'max' => 1500,
                    ]),
                    new NotBlank(),
                ],
                'label' => 'pel.facts.description.precise',
                'attr' => [
                    'class' => 'fr-input',
                    'data-counter-target' => 'parent',
                    'minlength' => 30,
                    'maxlength' => 1500,
                ],
            ])
            ->add('victimOfViolence', CheckboxType::class, [
                'label' => 'pel.victim.of.violence',
                'help' => 'pel.victim.of.violence.help',
                'required' => false,
            ])
            ->add('placeNature', ChoiceType::class, [
                'choices' => $this->naturePlaceThesaurusProvider->getChoices(),
                'label' => 'pel.nature.place',
                'placeholder' => 'pel.nature.place.choose',
                'required' => false,
            ])
            ->add('offenseDate', OffenseDateType::class, [
                'label' => false,
                'required' => true,
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var FactsModel $factsModel */
                    $factsModel = $event->getData();
                    $form = $event->getForm();
                    $this->addAddressField($form);
                    $this->addVictimOfViolenceField($form, $factsModel->isVictimOfViolence());
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

    private function addAddressField(FormInterface $form): void
    {
        $form->add('address', FactAddressType::class, [
            'label' => false,
            'compound' => true,
        ]);
    }

    private function addVictimOfViolenceField(
        FormInterface $form,
        ?bool $victimOfViolence,
        FactsModel $factsModel = null
    ): void {
        if (true === $victimOfViolence) {
            $form->add('victimOfViolenceText', TextareaType::class, [
                'label' => 'pel.victim.of.violence.text',
                'attr' => [
                    'placeholder' => 'pel.victim.of.violence.text.placeholder',
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FactsModel::class,
            'attr' => [
                'novalidate' => true,
            ],
        ]);
    }
}
