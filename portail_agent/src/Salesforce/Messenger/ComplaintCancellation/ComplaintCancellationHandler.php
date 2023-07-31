<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\ComplaintCancellation;

use App\Entity\Complaint;
use App\Repository\ComplaintRepository;
use App\Salesforce\SalesForceComplaintNotifier;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ComplaintCancellationHandler
{
    public function __construct(
        private readonly ComplaintRepository $complaintRepository,
        private readonly SalesForceComplaintNotifier $notifier
    ) {
    }

    public function __invoke(ComplaintCancellationMessage $message): void
    {
        /** @var ?Complaint $complaint */
        $complaint = $this->complaintRepository->find($message->getComplaintId());

        if (null === $complaint) {
            return;
        }

        $this->notifier->cancellation($complaint);
    }
}
