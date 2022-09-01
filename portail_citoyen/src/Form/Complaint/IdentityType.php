<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Enum\DeclarantStatus;
use App\Form\CivilStateType;
use App\Form\ContactInformationType;
use App\Form\CorporationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class IdentityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('declarantStatus', ChoiceType::class, [
            'label' => 'complaint.identity.declarant.status',
            'expanded' => true,
            'multiple' => false,
            'rich' => true,
            'choices' => DeclarantStatus::getChoices(),
        ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    $this->buildFieldsForDeclarantStatus($event->getForm());
                }
            )
            ->get('declarantStatus')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    /** @var FormInterface $parent */
                    $parent = $event->getForm()->getParent();
                    $this->buildFieldsForDeclarantStatus($parent, intval($event->getData()));
                }
            );
    }

    protected function buildFieldsForDeclarantStatus(FormInterface $form, ?int $declarantStatus = null): void
    {
        if (null === $declarantStatus) {
            return;
        }

        switch ($declarantStatus) {
            case DeclarantStatus::PersonLegalRepresentative->value:
                $this->addRepresentedPersonFields($form, $declarantStatus);
                break;
            case DeclarantStatus::Victim->value:
                break;
            case DeclarantStatus::CorporationLegalRepresentative->value:
                $this->addCorporationFields($form);
                break;
            default:
            }

        $this->addCivilStateAndContactInformationFields($form);
    }

    public function addCorporationFields(FormInterface $form): void
    {
        $form->add('corporation', CorporationType::class);
    }

    public function addCivilStateAndContactInformationFields(FormInterface $form): void
    {
        $form
            ->add('civilState', CivilStateType::class)
            ->add('contactInformation', ContactInformationType::class);
    }

    public function addRepresentedPersonFields(FormInterface $form, int $declarantStatus): void
    {
        $form
            ->add('representedPersonCivilState', CivilStateType::class, [
                'declarant_status' => $declarantStatus,
                'label' => 'civil.state',
            ])
            ->add('representedPersonContactInformation', ContactInformationType::class, [
                'label' => 'contact.information',
            ]);
    }
}
