<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\Enum\DeclarantStatus;
use App\Form\Model\Identity\IdentityModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class IdentityType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civilState', CivilStateType::class, [
                'compound' => true,
                'is_france_connected' => $options['is_france_connected'],
                'birthDate_constraints' => [
                    new NotBlank(),
                    new LessThanOrEqual(
                        '-18 years', message: $this->translator->trans(
                            'pel.you.must.have.more.than.error',
                            [
                                'age' => '18',
                            ],
                            'validators'
                        )
                    ),
                    new GreaterThanOrEqual(
                        '-120 years', message: $this->translator->trans(
                            'pel.you.must.have.less.than.error',
                            [
                                'age' => '120',
                            ],
                            'validators'
                        )
                    ),
                ],
            ])
            ->add('contactInformation', ContactInformationType::class, [
                'compound' => true,
                'is_france_connected' => $options['is_france_connected'],
            ])
            ->add('declarantStatus', ChoiceType::class, [
                'label' => 'pel.complaint.identity.declarant.status',
                'expanded' => true,
                'multiple' => false,
                'rich' => true,
                'choices' => DeclarantStatus::getChoices(),
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var ?IdentityModel $identityModel */
                    $identityModel = $event->getData();
                    $this->buildFieldsForDeclarantStatus(
                        $event->getForm(),
                        $identityModel?->getDeclarantStatus(),
                        $identityModel
                    );
                }
            )
            ->get('declarantStatus')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    /** @var FormInterface $parent */
                    $parent = $event->getForm()->getParent();
                    /** @var IdentityModel $identityModel */
                    $identityModel = $parent->getData();
                    $this->buildFieldsForDeclarantStatus($parent, intval($event->getData()), $identityModel);
                }
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IdentityModel::class,
            'is_france_connected' => false,
        ]);
    }

    private function buildFieldsForDeclarantStatus(
        FormInterface $form,
        ?int $declarantStatus = null,
        ?IdentityModel $identityModel = null
    ): void {
        if (null === $declarantStatus) {
            return;
        }

        switch ($declarantStatus) {
            case DeclarantStatus::PersonLegalRepresentative->value:
                $this->removeCorporationFields($form, $identityModel);
                $this->addRepresentedPersonFields($form);
                break;
            case DeclarantStatus::Victim->value:
                $this->removeRepresentedPersonFields($form, $identityModel);
                $this->removeCorporationFields($form, $identityModel);
                break;
            case DeclarantStatus::CorporationLegalRepresentative->value:
                $this->removeRepresentedPersonFields($form, $identityModel);
                $this->addCorporationFields($form);
                break;
            default:
        }
    }

    private function addCorporationFields(FormInterface $form): void
    {
        $form->add('corporation', CorporationType::class, [
            'need_same_address_field' => true,
        ]);
    }

    private function removeCorporationFields(FormInterface $form, ?IdentityModel $identityModel = null): void
    {
        $form->remove('corporation');
        $identityModel?->setCorporation(null);
    }

    private function addRepresentedPersonFields(FormInterface $form): void
    {
        $form
            ->add('representedPersonCivilState', CivilStateType::class, [
                'label' => 'pel.civil.state',
            ])
            ->add('representedPersonContactInformation', ContactInformationType::class, [
                'label' => 'pel.contact.information',
                'need_same_address_field' => true,
            ]);
    }

    private function removeRepresentedPersonFields(FormInterface $form, ?IdentityModel $identityModel = null): void
    {
        $form
            ->remove('representedPersonCivilState')
            ->remove('representedPersonContactInformation');

        $identityModel
            ?->setRepresentedPersonCivilState(null)
            ->setRepresentedPersonContactInformation(null);
    }
}
