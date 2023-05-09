<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\AppointmentType;
use App\Messenger\Appointment\AppointmentMessage;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentValidationController extends AbstractController
{
    #[Route(path: '/plainte/validation-rendez-vous/{id}', name: 'complaint_appointment_validate', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
        Request $request,
        MessageBusInterface $messageBus
    ): JsonResponse {
        $form = $this->createForm(AppointmentType::class, $complaint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $complaintRepository->save($complaint, true);

            $messageBus->dispatch(new AppointmentMessage($complaint));

            return $this->json(['success' => true]);
        }

        return $this->json([
            'success' => false,
            'form' => $this->renderView(
                'pages/complaint/_partial/appointment_content.html.twig',
                [
                    'appointment_form' => $form->createView(),
                    'complaint' => $complaint,
                ]
            ),
        ], 422);
    }
}
