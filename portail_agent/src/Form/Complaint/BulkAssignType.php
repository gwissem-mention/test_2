<?php

namespace App\Form\Complaint;

use App\Entity\User;
use App\Form\DTO\BulkAssignAction;
use App\Form\EventListener\AssignAgentEventSubscriber;
use App\Form\UserAutocompleteType;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BulkAssignType extends AbstractType
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventSubscriber(
            new class($this->userRepository) extends AssignAgentEventSubscriber {
                public function buildAgentField(FormInterface $form, ?User $user): void
                {
                    $form->add('assignedTo', UserAutocompleteType::class,
                        [
                            'choices' => !is_null($user) ? [$user->getAppellation() => $user->getId()] : null,
                            'attr' => [
                                'required' => true,
                            ],
                            'constraints' => [
                                new NotBlank(),
                            ],
                        ]
                    )->add('complaints', HiddenType::class, [
                        'attr' => [
                            'data-dashboard-bulk-action-target' => 'bulkAssignComplaints',
                        ],
                    ]);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BulkAssignAction::class,
        ]);
    }
}
