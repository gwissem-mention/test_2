<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\Enum\DeclarantStatus;
use App\FranceConnect\IdentitySessionHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class IdentityType extends AbstractType
{
    public function __construct(
        private readonly IdentitySessionHandler $fcIdentitySessionHandler
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('declarantStatus', ChoiceType::class, [
            'label' => 'pel.complaint.identity.declarant.status',
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

    private function buildFieldsForDeclarantStatus(FormInterface $form, ?int $declarantStatus = null): void
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

    private function addCorporationFields(FormInterface $form): void
    {
        $form->add('corporation', CorporationType::class);
    }

    private function addCivilStateAndContactInformationFields(FormInterface $form): void
    {
        $form
            ->add('civilState', CivilStateType::class, [
                'compound' => true,
                'fc_identity' => $this->fcIdentitySessionHandler->getIdentity(),
                'birthDate_constraints' => [
                    new NotBlank(),
                    new LessThanOrEqual('-18 years'),
                    new GreaterThanOrEqual('-120 years'),
                ],
            ])
            ->add('contactInformation', ContactInformationType::class);
    }

    private function addRepresentedPersonFields(FormInterface $form, int $declarantStatus): void
    {
        $form
            ->add('representedPersonCivilState', CivilStateType::class, [
                'declarant_status' => $declarantStatus,
                'label' => 'pel.civil.state',
            ])
            ->add('representedPersonContactInformation', ContactInformationType::class, [
                'label' => 'pel.contact.information',
            ]);
    }
}
