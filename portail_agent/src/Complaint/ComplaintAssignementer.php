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
        $this->assignTo($complaint, $user, $reassignment);
        $this->entityManager->flush();
    }

    /**
     * @param Complaint[] $complaints
     */
    public function assignBulkTo(array $complaints, User $user): void
    {
        foreach ($complaints as $complaint) {
            $reassignment = $complaint->getAssignedTo() instanceof User;
            $this->assignTo($complaint, $user, $reassignment);
        }

        $this->entityManager->flush();
    }

    private function assignTo(Complaint $complaint, User $user, bool $isReassignement): void
    {
        $complaint->setAssignedTo($user);
        $complaint->setStatus(Complaint::STATUS_ASSIGNED);

        $this->messageBus->dispatch(new AssignementMessage($complaint, $user, $isReassignement));
    }
}
