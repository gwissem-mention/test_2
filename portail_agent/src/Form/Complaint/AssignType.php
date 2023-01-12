<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Entity\Complaint;
use App\Form\AgentAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AssignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    $this->addAgentField($event->getForm());
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

    private function addAgentField(FormInterface $form, ?string $agent = null): void
    {
        $form
            ->add('assignedTo', AgentAutocompleteType::class, [
                'choices' => !is_null($agent) ? [intval($agent)] : null,
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
