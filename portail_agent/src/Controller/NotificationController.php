<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class NotificationController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/notification/{id}', name: 'notification')]
    public function __invoke(Notification $notification, NotificationRepository $notificationRepository): RedirectResponse
    {
        $notificationRepository->save($notification->setClickedAt(new \DateTimeImmutable()), true);

        return $this->redirect($notification->getRedirectionUrl());
    }
}
