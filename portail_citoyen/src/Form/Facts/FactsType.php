<?php

declare(strict_types=1);

namespace App\Form\Facts;

use App\Form\Model\Facts\FactsModel;
use App\Form\Model\Identity\ContactInformationModel;
use App\Session\SessionHandler;
use App\Thesaurus\NaturePlaceThesaurusProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class FactsType extends AbstractType
{
    public function __construct(private readonly NaturePlaceThesaurusProviderInterface $naturePlaceThesaurusProvider, private readonly SessionHandler $sessionHandler)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'max' => 800,
                    ]),
                ],
                'label' => 'pel.facts.description.precise',
                'attr' => [
                    'placeholder' => 'pel.facts.describe.whats.happened',
                ],
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
            ->add('offenseDate', OffenseDateType::class, [
                'label' => false,
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

        $builder->get('placeNature')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var array<string, int> $homePlaceNature */
                $homePlaceNature = $this->naturePlaceThesaurusProvider->getChoices();

                if ((string) $homePlaceNature['pel.nature.place.home'] === $event->getData()) {
                    /** @var ContactInformationModel $contactInformation */
                    $contactInformation = $this->sessionHandler->getComplaint()?->getIdentity()?->getContactInformation();
                    /** @var FormInterface $parent */
                    $parent = $event->getForm()->getParent();

                    $this->addAddressField(
                        $parent,
                        $contactInformation->getFrenchAddress()?->getLabel() ??
                        $contactInformation->getForeignAddress()
                    );
                }
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

    private function addAddressField(FormInterface $form, ?string $address = null): void
    {
        $form->add('address', AddressType::class, [
            'label' => false,
            'compound' => true,
            'start_address' => $address,
        ]);
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FactsModel::class,
        ]);
    }
}
