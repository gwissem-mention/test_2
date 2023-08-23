<?php

declare(strict_types=1);

namespace App\Form\Objects;

use App\AppEnum\DocumentType;
use App\Form\Model\Objects\ObjectModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DocumentTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('documentType', ChoiceType::class, [
                'choices' => DocumentType::getChoices(),
                'placeholder' => 'pel.object.document.type.choose',
                'label' => 'pel.document.type',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('documentOwned', ChoiceType::class, [
                'choices' => [
                    'pel.yes' => true,
                    'pel.no' => false,
                ],
                'expanded' => true,
                'label' => 'pel.i.am.the.owner.of.this.document',
                'inline' => true,
                'multiple' => false,
                'empty_data' => '1',
                'priority' => -1,
            ])
            ->add('documentNumber', TextType::class, [
                'label' => 'pel.document.number',
                'required' => false,
                'priority' => -2,
            ])
            ->add('documentIssuedBy', TextType::class, [
                'label' => 'pel.document.issued.by',
                'required' => false,
                'priority' => -2,
            ])
            ->add('documentIssuedOn', DateType::class, [
                'widget' => 'single_text',
                'label' => 'pel.document.issued.on',
                'required' => false,
                'priority' => -2,
            ])
            ->add('documentValidityEndDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'pel.document.validity.end.date',
                'required' => false,
                'priority' => -2,
            ]);

        $builder->get('documentType')->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var ?int $documentType */
                $documentType = $event->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                if (DocumentType::Other->value === $documentType) {
                    $this->addDocumentTypeOtherFields($parent);
                } else {
                    $this->removeDocumentTypeOtherFields($parent);
                }
            }
        );

        $builder->get('documentType')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var ?int $documentType */
                $documentType = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var ?ObjectModel $objectModel */
                $objectModel = $parent->getData();
                if (DocumentType::Other->value === $documentType) {
                    $this->addDocumentTypeOtherFields($parent);
                } else {
                    $this->removeDocumentTypeOtherFields($parent, $objectModel);
                }
            }
        );

        $builder->get('documentOwned')->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var bool $documentOwned */
                $documentOwned = $event->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                if (false === $documentOwned) {
                    $this->addDocumentOwnerFields($parent);
                } else {
                    $this->removeDocumentOwnerFields($parent);
                }
            }
        );

        $builder->get('documentOwned')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var bool $documentOwned */
                $documentOwned = $event->getForm()->getData();
                /** @var FormInterface $parent */
                $parent = $event->getForm()->getParent();
                /** @var ?ObjectModel $objectModel */
                $objectModel = $parent->getData();
                if (false === $documentOwned) {
                    $this->addDocumentOwnerFields($parent);
                } else {
                    $this->removeDocumentOwnerFields($parent, $objectModel);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => ObjectModel::class,
            'inherit_data' => true,
        ]);
    }

    private function addDocumentTypeOtherFields(FormInterface $form): void
    {
        $form->add('otherDocumentType', TextType::class, [
            'label' => 'pel.could.you.precise',
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }

    private function removeDocumentTypeOtherFields(FormInterface $form, ObjectModel $objectModel = null): void
    {
        $form->remove('otherDocumentType');

        $objectModel?->setOtherDocumentType(null);
    }

    private function addDocumentOwnerFields(FormInterface $form): void
    {
        $form
            ->add('documentAdditionalInformation', DocumentAdditionalInformationType::class, [
                'label' => false,
                'priority' => -2,
            ])
        ;
    }

    private function removeDocumentOwnerFields(FormInterface $form, ObjectModel $objectModel = null): void
    {
        $form->remove('documentAdditionalInformation');

        $objectModel?->setDocumentAdditionalInformation(null);
    }
}
