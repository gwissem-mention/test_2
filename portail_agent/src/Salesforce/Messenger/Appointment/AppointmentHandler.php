<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\Appointment;

use App\Entity\Complaint;
use App\Entity\User;
use App\Repository\ComplaintRepository;
use App\Salesforce\SalesForceComplaintNotifier;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AppointmentHandler
{
    public function __construct(
        private readonly ComplaintRepository $complaintRepository,
        private readonly SalesForceComplaintNotifier $notifier,
        private readonly Security $security
    ) {
    }

    public function __invoke(AppointmentMessage $message): void
    {
        /** @var ?Complaint $complaint */
        $complaint = $this->complaintRepository->find($message->getComplaintId());

        if (null === $complaint) {
            return;
        }

        /** @var User $user */
        $user = $this->security->getUser();

        $this->notifier->appointmentWarmup($complaint, (string) $user->getTimezone());
    }
}
