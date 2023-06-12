<?php

namespace App\Messenger\UnitReassignement;

use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AskUnitReassignementHandler
{
    public function __construct(
        private readonly NotificationFactory $notificationFactory,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(AskUnitReassignementMessage $message): void
    {
        $notification = $this->notificationFactory->createForComplaintUnitReassignmentOrdered($message->getComplaint());
        $supervisors = $this->userRepository->getSupervisorsByService($message->getUnitCode());

        foreach ($supervisors as $supervisor) {
            $supervisor->addNotification($notification);
        }

        $this->entityManager->flush();
    }
}
