<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/porter-plainte/statut-declarant/{fromSummary?0}', name: 'complaint_declarant_status', requirements: ['fromSummary' => '0|1'], methods: ['GET'])]
class DeclarantStatusController extends AbstractController
{
    public function __invoke(
        Request $request,
        SessionHandler $sessionHandler,
        bool $fromSummary = false
    ): Response {
        $sessionHandler->init();

        return $this->render('pages/complaint_declarant_status.html.twig', [
            'complaint' => $sessionHandler->getComplaint(), 'from_summary' => $fromSummary,
        ]);
    }
}
