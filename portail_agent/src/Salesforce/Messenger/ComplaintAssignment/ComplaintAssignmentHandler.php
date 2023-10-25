<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\ComplaintAssignment;

use App\Entity\Complaint;
use App\Repository\ComplaintRepository;
use App\Salesforce\SalesForceComplaintNotifier;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ComplaintAssignmentHandler
{
    public function __construct(
        private readonly ComplaintRepository $complaintRepository,
        private readonly SalesForceComplaintNotifier $notifier
    ) {
    }

    public function __invoke(ComplaintAssignmentMessage $message): void
    {
        /** @var ?Complaint $complaint */
        $complaint = $this->complaintRepository->find($message->getComplaintId());

        if (null === $complaint || true === $complaint->isTest() || null !== $complaint->getAssignedTo()) {
            return;
        }

        $this->notifier->assignment($complaint);
    }
}
