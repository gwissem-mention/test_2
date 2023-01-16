<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Entity\Complaint;
use App\Form\AgentAutocompleteType;
use App\Referential\Entity\Agent;
use App\Referential\Repository\AgentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AssignType extends AbstractType
{
    public function __construct(private readonly AgentRepository $agentRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    /** @var Complaint $complaint */
                    $complaint = $event->getData();
                    $this->addAgentField(
                        $event->getForm(),
                        !is_null($complaint->getAssignedTo()) ? strval($complaint->getAssignedTo()) : null
                    );
                }
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    /** @var array<string, string> $data */
                    $data = $event->getData();
                    /** @var ?string $agent */
                    $agent = $data['assignedTo'];
                    $this->addAgentField($event->getForm(), !empty($agent) ? $agent : null);
                }
            );
    }

    private function addAgentField(FormInterface $form, ?string $agentId = null): void
    {
        $agent = null;
        if (!is_null($agentId)) {
            /** @var Agent $agent */
            $agent = $this->agentRepository->find($agentId);
        }

        $form
            ->add('assignedTo', AgentAutocompleteType::class, [
                'choices' => !is_null($agent) ? [$agent->getName() => $agent->getId()] : null,
                'attr' => [
                    'required' => true,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Complaint::class,
        ]);
    }
}
