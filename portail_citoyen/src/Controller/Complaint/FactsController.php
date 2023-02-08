<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/porter-plainte/faits', name: 'complaint_facts', methods: ['GET'])]
class FactsController extends AbstractController
{
    public function __invoke(
        Request $request,
        SessionHandler $sessionHandler
    ): Response {
        if (!$sessionHandler->getComplaint()?->isComplaintIdentityFilled()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('pages/complaint_facts.html.twig', ['complaint' => $sessionHandler->getComplaint()]);
    }
}
