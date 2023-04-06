<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Form\Model\Identity\DeclarantStatusModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/porter-plainte/identite/{fromSummary?0}', name: 'complaint_identity', requirements: ['fromSummary' => '0|1'], methods: ['GET'])]
class IdentityController extends AbstractController
{
    public function __invoke(
        Request $request,
        SessionHandler $sessionHandler,
        bool $fromSummary = false
    ): Response {
        if (!$sessionHandler->getComplaint()?->getDeclarantStatus() instanceof DeclarantStatusModel) {
            return $this->redirectToRoute('home');
        }

        return $this->render('pages/complaint_identity.html.twig', ['complaint' => $sessionHandler->getComplaint(), 'from_summary' => $fromSummary]);
    }
}
