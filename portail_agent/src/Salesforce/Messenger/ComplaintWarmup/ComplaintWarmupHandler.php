<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\ComplaintWarmup;

use App\Entity\Complaint;
use App\Referential\Repository\UnitRepository;
use App\Repository\ComplaintRepository;
use App\Salesforce\SalesForceComplaintNotifier;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ComplaintWarmupHandler
{
    public function __construct(
        private readonly ComplaintRepository $complaintRepository,
        private readonly SalesForceComplaintNotifier $notifier,
        private readonly UnitRepository $unitRepository
    ) {
    }

    public function __invoke(ComplaintWarmupMessage $message): void
    {
        /** @var ?Complaint $complaint */
        $complaint = $this->complaintRepository->find($message->getComplaintId());

        if (null === $complaint || null === $complaint->getUnitAssigned()) {
            return;
        }

        $unit = $this->unitRepository->findOneBy(['serviceId' => $complaint->getUnitAssigned()]);

        if (null === $unit) {
            return;
        }

        $this->notifier->startJourney($complaint);
        $this->notifier->appointmentInit($complaint);
    }
}
