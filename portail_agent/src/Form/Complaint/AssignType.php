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
                        !is_null($complaint->getAssignedTo()) ? strval($complaint->getAssignedTo()) : null
                    );
                }
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    /** @var array<string, string> $data */
                    $data = $event->getData();
                    /** @var ?string $user */
                    $user = $data['assignedTo'];
                    $this->addAgentField($event->getForm(), !empty($user) ? $user : null);
                }
            );
    }

    private function addAgentField(FormInterface $form, ?string $userId = null): void
    {
        $user = null;
        if (!is_null($userId)) {
            /** @var User $user */
            $user = $this->userRepository->find($userId);
        }

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
