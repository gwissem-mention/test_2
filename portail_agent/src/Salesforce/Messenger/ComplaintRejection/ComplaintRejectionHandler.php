<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\ComplaintRejection;

use App\Entity\Complaint;
use App\Repository\ComplaintRepository;
use App\Salesforce\SalesForceComplaintNotifier;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ComplaintRejectionHandler
{
    public function __construct(
        private readonly ComplaintRepository $complaintRepository,
        private readonly SalesForceComplaintNotifier $notifier
    ) {
    }

    public function __invoke(ComplaintRejectionMessage $message): void
    {
        /** @var ?Complaint $complaint */
        $complaint = $this->complaintRepository->find($message->getComplaintId());

        if (null === $complaint || true === $complaint->isTest()) {
            return;
        }

        $this->notifier->rejection($complaint);
    }
}
