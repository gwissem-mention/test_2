<?php

declare(strict_types=1);

namespace App\Form\Identity;

use App\AppEnum\DeclarantStatus;
use App\Form\Model\Identity\IdentityModel;
use App\Session\SessionHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
        private readonly TranslatorInterface $translator,
        private readonly SessionHandler $sessionHandler
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('declarantStatus', ChoiceType::class, [
                'label' => 'pel.complaint.identity.declarant.status',
                'expanded' => true,
                'multiple' => false,
                'rich' => true,
                'choices' => DeclarantStatus::getChoices(),
                'choices_help' => DeclarantStatus::getChoicesHelp(),
                'choices_img' => DeclarantStatus::getChoicesImg(),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
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
            ->add('consentContactElectronics', CheckboxType::class, [
                'required' => false,
                'label' => 'pel.consent.confirmation',
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var ?IdentityModel $identityModel */
                    $identityModel = $event->getData();
                    $declarantStatus = $this->sessionHandler->getComplaint()?->getIdentity()?->getDeclarantStatus();

                    $this->buildFieldsForDeclarantStatus(
                        $event->getForm(),
                        $declarantStatus,
                        $identityModel
                    );
                }
            )
            ->get('declarantStatus')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    /** @var ?int $declarantStatus */
                    $declarantStatus = $event->getForm()->getData();
                    /** @var FormInterface $parent */
                    $parent = $event->getForm()->getParent();

                    $this->buildFieldsForDeclarantStatus(
                        $parent,
                        $declarantStatus,
                    );
                }
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IdentityModel::class,
            'is_france_connected' => false,
            'attr' => [
                'novalidate' => true,
            ],
        ]);
    }

    private function buildFieldsForDeclarantStatus(
        FormInterface $form,
        int $declarantStatus = null,
        IdentityModel $identityModel = null
    ): void {
        if (null === $declarantStatus) {
            return;
        }

        switch ($declarantStatus) {
            /* Person Legal Representative must be hidden for the experimentation */
            // case DeclarantStatus::PersonLegalRepresentative->value:
            //     $this->removeCorporationFields($form, $identityModel);
            //     $this->addRepresentedPersonFields($form);
            //    break;
            case DeclarantStatus::Victim->value:
                /* Person Legal Representative must be hidden for the experimentation */
                // $this->removeRepresentedPersonFields($form, $identityModel);
                $this->removeCorporationFields($form, $identityModel);
                break;
            case DeclarantStatus::CorporationLegalRepresentative->value:
                /* Person Legal Representative must be hidden for the experimentation */
                // $this->removeRepresentedPersonFields($form, $identityModel);
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

    private function removeCorporationFields(FormInterface $form, IdentityModel $identityModel = null): void
    {
        $form->remove('corporation');
        $identityModel?->setCorporation(null);
    }

    /* Person Legal Representative must be hidden for the experimentation */
    // private function addRepresentedPersonFields(FormInterface $form): void
    // {
    //     $form
    //         ->add('representedPersonCivilState', CivilStateType::class, [
    //             'label' => 'pel.civil.state',
    //         ])
    //         ->add('representedPersonContactInformation', ContactInformationType::class, [
    //             'label' => 'pel.contact.information',
    //             'need_same_address_field' => true,
    //         ]);
    // }

    /* Person Legal Representative must be hidden for the experimentation */
    // private function removeRepresentedPersonFields(FormInterface $form, IdentityModel $identityModel = null): void
    // {
    //     $form
    //         ->remove('representedPersonCivilState')
    //         ->remove('representedPersonContactInformation');
    //
    //     $identityModel
    //         ?->setRepresentedPersonCivilState(null)
    //         ->setRepresentedPersonContactInformation(null);
    // }
}
