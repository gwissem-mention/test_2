<?php

namespace App\Complaint;

use App\Entity\Complaint;
use App\Messenger\UnitReassignement\AskUnitReassignementMessage;
use App\Messenger\UnitReassignement\UnitReassignementMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\MessageBusInterface;

class ComplaintReassignementer
{
    public function __construct(
        private readonly Security $security,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @param array<Complaint> $complaints
     */
    public function reassignBulkTo(array $complaints, string $assignedUnitCode, string $reassignText): void
    {
        if ($this->security->isGranted('ROLE_SUPERVISOR')) {
            foreach ($complaints as $complaint) {
                $this->reassignAsSupervisor($complaint, $assignedUnitCode);
            }
        } else {
            foreach ($complaints as $complaint) {
                $this->askReassignement($complaint, $assignedUnitCode, $reassignText);
            }
        }

        $this->entityManager->flush();
    }

    public function reassignAsSupervisor(Complaint $complaint, string $targetUnitCode): void
    {
        $complaint->setStatus(Complaint::STATUS_ASSIGNMENT_PENDING);

        $complaint->setUnitAssigned($targetUnitCode);
        $complaint->setUnitToReassign(null);
        $complaint->setUnitReassignText(null);
        $complaint->setAssignedTo(null);

        $this->messageBus->dispatch(new UnitReassignementMessage($complaint, $targetUnitCode));
    }

    public function askReassignement(Complaint $complaint, string $targetUnitCode, string $reassignText): void
    {
        $complaint->setStatus(Complaint::STATUS_UNIT_REDIRECTION_PENDING);
        $complaint->setUnitToReassign($targetUnitCode);
        $complaint->setUnitReassignText($reassignText);

        $this->messageBus->dispatch(new AskUnitReassignementMessage($complaint, $targetUnitCode));
    }
}
