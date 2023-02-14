<?php

declare(strict_types=1);

namespace App\Form\Complaint;

use App\Entity\Complaint;
use App\Entity\User;
use App\Form\UserAutocompleteType;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AssignType extends AbstractType
{
    public function __construct(private readonly UserRepository $userRepository)
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
                        $complaint->getAssignedTo()
                    );
                }
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    /** @var array<string, string> $data */
                    $data = $event->getData();
                    $user = $data['assignedTo'];
                    $this->addAgentField($event->getForm(), $this->userRepository->find($user));
                }
            );
    }

    private function addAgentField(FormInterface $form, ?User $user = null): void
    {
        $form
            ->add('assignedTo', UserAutocompleteType::class, [
                'choices' => !is_null($user) ? [$user->getAppellation() => $user->getId()] : null,
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
