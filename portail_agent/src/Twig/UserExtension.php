<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\User;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UserExtension extends AbstractExtension
{
    public function __construct(private readonly UserRepository $userRepository,
                                private readonly NotificationRepository $notificationRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('user_appellation', [$this, 'getAppellation']),
            new TwigFilter('user_unclicked_notifications', [$this, 'getUnclickedNotifications']),
        ];
    }

    public function getAppellation(?int $id): ?string
    {
        if (is_null($id)) {
            return null;
        }

        return $this->userRepository->find($id)?->getAppellation();
    }

    public function getUnclickedNotifications(User $user): mixed
    {
        return $this->notificationRepository->createQueryBuilder('notification')
            ->where('notification.user = :user')
            ->andWhere('notification.clickedAt IS NULL')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
