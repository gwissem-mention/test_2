<?php

namespace App\Messenger\UnitReassignement;

use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UnitReassignementHandler
{
    public function __construct(
        private readonly NotificationFactory $notificationFactory,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    ) {
    }

    public function __invoke(UnitReassignementMessage $message): void
    {
        $notification = $this->notificationFactory->createForComplaintUnitReassignment($message->getComplaint());
        $supervisors = $this->userRepository->getSupervisorsByUnit($message->getUnitCode());

        foreach ($supervisors as $supervisor) {
            $supervisor->addNotification($notification);
        }

        if (true === $message->isReassignmentAsked() && $message->getReassignmentAskBy() instanceof User) {
            /** @var User $supervisor */
            $supervisor = $this->security->getUser();
            $message->getReassignmentAskBy()->addNotification(
                $this->notificationFactory->createForComplaintReassignmentValidated($message->getComplaint(), $supervisor)
            );
        }

        $this->entityManager->flush();
    }
}
