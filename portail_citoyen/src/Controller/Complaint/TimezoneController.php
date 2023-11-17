<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/porter-plainte/timezone-utilisateur', name: 'complaint_timezone', methods: ['POST'])]
class TimezoneController extends AbstractController
{
    public function __invoke(Request $request, SessionHandler $sessionHandler): JsonResponse
    {
        /** @var array<string, string> $requestContent */
        $requestContent = json_decode($request->getContent(), true);

        /** @var ComplaintModel $complaint */
        $complaint = $sessionHandler->getComplaint();

        $sessionHandler->setComplaint(
            $complaint->setTimezone($requestContent['timezone'])
        );

        return new JsonResponse();
    }
}
