<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Form\Model\AdditionalInformation\AdditionalInformationModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/porter-plainte/recapitulatif', name: 'complaint_summary', methods: ['GET'])]
class SummaryController extends AbstractController
{
    public function __invoke(SessionHandler $sessionHandler): Response
    {
        if (!$sessionHandler->getComplaint()?->getAdditionalInformation() instanceof AdditionalInformationModel) {
            return $this->redirectToRoute('home');
        }

        return $this->render('pages/summary.html.twig', [
            'complaint' => $sessionHandler->getComplaint(),
        ]);
    }
}
