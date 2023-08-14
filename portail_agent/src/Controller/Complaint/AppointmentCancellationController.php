<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Repository\ComplaintRepository;
use App\Salesforce\Messenger\ComplaintCancellation\ComplaintCancellationMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AppointmentCancellationController extends AbstractController
{
    #[IsGranted('COMPLAINT_VIEW', subject: 'complaint')]
    #[Route(path: '/plainte/annulation-rendez-vous/{id}', name: 'complaint_appointment_cancel', methods: ['POST'])]
    public function __invoke(Complaint $complaint, ComplaintRepository $complaintRepository, MessageBusInterface $bus): JsonResponse
    {
        $complaint
            ->setAppointmentDate(null)
            ->setAppointmentTime(null);

        $bus->dispatch(new ComplaintCancellationMessage((int) $complaint->getId())); // salesforce

        $complaint->incrementAppointmentCancellationCounter();
        $complaintRepository->save($complaint, true);

        return new JsonResponse();
    }
}
