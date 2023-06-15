<?php

declare(strict_types=1);

namespace App\Complaint;

use App\Entity\Complaint;
use App\Entity\User;
use App\Notification\Messenger\Assignement\AssignementMessage;
use App\Salesforce\Messenger\ComplaintAssignment\ComplaintAssignmentMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ComplaintAssignementer
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $messageBus,
        private readonly ComplaintWorkflowManager $complaintWorkflowManager,
    ) {
    }

    /**
     * @throws ComplaintWorkflowException
     */
    public function assignOneTo(Complaint $complaint, User $user, bool $isReassignment, bool $isSelfAssignment): void
    {
        $this->assignTo($complaint, $user, $isReassignment, $isSelfAssignment);
        $this->entityManager->flush();
    }

    /**
     * @param Complaint[] $complaints
     *
     * @throws ComplaintWorkflowException
     */
    public function assignBulkTo(array $complaints, User $user): void
    {
        foreach ($complaints as $complaint) {
            $isReassignment = $complaint->getAssignedTo() instanceof User;
            $this->assignTo($complaint, $user, $isReassignment, false);
        }

        $this->entityManager->flush();
    }

    /**
     * @throws ComplaintWorkflowException
     */
    private function assignTo(Complaint $complaint, User $user, bool $isReassignment, bool $isSelfAssignment): void
    {
        if ($isSelfAssignment) {
            $this->complaintWorkflowManager->selfAssign($complaint);
        } else {
            $this->complaintWorkflowManager->assign($complaint);
        }

        $complaint->setAssignedTo($user);

        $this->messageBus->dispatch(new AssignementMessage($complaint, $user, $isReassignment));
        $this->messageBus->dispatch(new ComplaintAssignmentMessage((int) $complaint->getId())); // Salesforce email
    }
}
