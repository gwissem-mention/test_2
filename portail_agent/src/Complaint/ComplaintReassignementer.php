<?php

declare(strict_types=1);

namespace App\Complaint;

use App\Entity\Comment;
use App\Entity\Complaint;
use App\Entity\User;
use App\Notification\Messenger\UnitReassignement\AskUnitReassignementMessage;
use App\Notification\Messenger\UnitReassignement\UnitReassignementMessage;
use App\Referential\Entity\Unit;
use App\Referential\Repository\UnitRepository;
use App\Salesforce\Messenger\UnitReassignment\UnitReassignmentMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\MessageBusInterface;

class ComplaintReassignementer
{
    public function __construct(
        private readonly Security $security,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $messageBus,
        private readonly ComplaintWorkflowManager $complaintWorkflowManager,
        private readonly UnitRepository $unitRepository
    ) {
    }

    /**
     * @param array<Complaint> $complaints
     *
     * @throws ComplaintWorkflowException
     */
    public function reassignBulkTo(array $complaints, string $assignedUnitCode, string $reassignText): void
    {
        if ($this->security->isGranted('ROLE_SUPERVISOR')) {
            foreach ($complaints as $complaint) {
                $this->reassignAsSupervisor($complaint, $assignedUnitCode, $reassignText);
            }
        } else {
            foreach ($complaints as $complaint) {
                $this->askReassignement($complaint, $assignedUnitCode, $reassignText);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @throws ComplaintWorkflowException
     */
    public function reassignAsSupervisor(Complaint $complaint, string $targetUnitCode, string $reassignText): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $reassignmentAsked = $complaint->isUnitReassignmentAsked();
        $reassignmentAskBy = $complaint->getAssignedTo();

        $comment = (new Comment())
            ->setAuthor($user)
            ->setTitle(Comment::UNIT_REASSIGNMENT_REASON)
            ->setContent($reassignText);

        $this->complaintWorkflowManager->unitRedirect($complaint);

        /** @var Unit $unit */
        $unit = $this->unitRepository->findOneBy(['code' => $targetUnitCode]);

        $complaint
            ->setUnitAssigned($unit->getServiceId())
            ->setUnitToReassign(null)
            ->setUnitReassignText(null)
            ->setAssignedTo(null)
            ->setUnitReassignmentAsked(false)
            ->addComment($comment);

        $this->messageBus->dispatch(new UnitReassignementMessage($complaint, $targetUnitCode, (bool) $reassignmentAsked, $reassignmentAskBy));
        $this->messageBus->dispatch(new UnitReassignmentMessage((int) $complaint->getId())); // Salesforce email
    }

    /**
     * @throws ComplaintWorkflowException
     */
    public function askReassignement(Complaint $complaint, string $targetUnitCode, string $reassignText): void
    {
        $this->complaintWorkflowManager->askUnitRedirection($complaint);

        $complaint
            ->setUnitToReassign($targetUnitCode)
            ->setUnitReassignText($reassignText)
            ->setUnitReassignmentAsked(true);

        $this->messageBus->dispatch(new AskUnitReassignementMessage($complaint, (string) $complaint->getUnitAssigned()));
    }
}
