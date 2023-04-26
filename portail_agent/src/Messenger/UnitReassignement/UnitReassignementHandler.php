<?php

namespace App\Messenger\UnitReassignement;

use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UnitReassignementHandler
{
    public function __construct(
        private readonly NotificationFactory $notificationFactory,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(UnitReassignementMessage $message): void
    {
        $notification = $this->notificationFactory->createForComplaintUnitReassignment($message->getComplaint());
        $supervisors = $this->userRepository->getSupervisorsByUnit($message->getUnitCode());

        foreach ($supervisors as $supervisor) {
            $supervisor->addNotification($notification);
        }

        $this->entityManager->flush();
    }
}
