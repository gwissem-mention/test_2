<?php

namespace App\Messenger\Assignement;

use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AssignementHandler
{
    public function __construct(
        private readonly NotificationFactory $notificationFactory,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(AssignementMessage $message): void
    {
        $notification = $this->notificationFactory->createForComplaintAssigned(
            $message->getComplaint(),
            $message->isReassignment()
        );

        $message->getUser()->addNotification($notification);

        $this->userRepository->save($message->getUser(), true);
    }
}
