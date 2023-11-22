<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\AppointmentType;
use App\Form\Complaint\AssignType;
use App\Form\Complaint\RejectType;
use App\Form\Complaint\SendReportType;
use App\Form\Complaint\UnitReassignType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AppointmentController extends AbstractController
{
    #[IsGranted('COMPLAINT_VIEW', subject: 'complaint')]
    #[Route(path: '/plainte/rendez-vous/{id}', name: 'complaint_appointment', methods: ['GET'])]
    public function __invoke(Complaint $complaint): Response
    {
        return $this->render('pages/complaint/appointment.html.twig', [
            'complaint' => $complaint,
            'reject_form' => $this->createForm(RejectType::class, $complaint),
            'assign_form' => $this->createForm(AssignType::class, $complaint),
            'unit_reassign_form' => $this->createForm(UnitReassignType::class, $complaint),
            'send_report_form' => $this->createForm(SendReportType::class, null, [
                'has_scheduled_appointment' => null !== $complaint->getAppointmentDate(),
            ]),
            'appointment_form' => $this->createForm(AppointmentType::class, $complaint),
            'is_appointment_planned' => null !== $complaint->getAppointmentDate(),
        ]);
    }
}
