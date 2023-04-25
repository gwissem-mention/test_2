<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Form\Model\AdditionalInformation\AdditionalInformationModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    #[Route('/porter-plainte/rendez-vous', name: 'complaint_appointment', methods: ['GET'])]
    public function __invoke(SessionHandler $sessionHandler): Response
    {
        $complaint = $sessionHandler->getComplaint();

        if (!$complaint?->getAdditionalInformation() instanceof AdditionalInformationModel) {
            return $this->redirectToRoute('home');
        }

        return $this->render('pages/complaint_appointment.html.twig', [
            'complaint' => $complaint,
        ]);
    }
}
