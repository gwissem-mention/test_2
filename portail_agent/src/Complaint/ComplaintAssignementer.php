<?php

namespace App\Complaint;

use App\Entity\Complaint;
use App\Entity\User;
use App\Messenger\Assignement\AssignementMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ComplaintAssignementer
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function assignOneTo(Complaint $complaint, User $user, bool $reassignment): void
    {
        $complaint->setAssignedTo($user);
        $this->entityManager->flush();

        $this->messageBus->dispatch(new AssignementMessage($complaint, $user, $reassignment));
    }

    /**
     * @param Complaint[] $complaints
     */
    public function assignBulkTo(array $complaints, User $user): void
    {
        foreach ($complaints as $complaint) {
            $reassignment = $complaint->getAssignedTo() instanceof User;

            $complaint->setAssignedTo($user);
            $this->messageBus->dispatch(new AssignementMessage($complaint, $user, $reassignment));
        }

        $this->entityManager->flush();
    }
}
