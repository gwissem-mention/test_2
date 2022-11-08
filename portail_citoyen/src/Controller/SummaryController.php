<?php

declare(strict_types=1);

namespace App\Controller;

use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SummaryController extends AbstractController
{
    #[Route('/recapitulatif', name: 'summary')]
    public function __invoke(SessionHandler $sessionHandler): Response
    {
        if (null === $sessionHandler->getComplaint()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('pages/summary.html.twig', [
            'complaint' => $sessionHandler->getComplaint(),
        ]);
    }
}
