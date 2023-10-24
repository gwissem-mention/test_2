<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Entity\User;
use App\Form\Complaint\AppointmentType;
use App\Logger\ApplicationTracesLogger;
use App\Logger\ApplicationTracesMessage;
use App\Messenger\InformationCenter\InfocentreMessage;
use App\Referential\Repository\UnitRepository;
use App\Repository\ComplaintRepository;
use App\Salesforce\Messenger\Appointment\AppointmentMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AppointmentValidationController extends AbstractController
{
    #[IsGranted('COMPLAINT_VIEW', subject: 'complaint')]
    #[Route(path: '/plainte/validation-rendez-vous/{id}', name: 'complaint_appointment_validate', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
        Request $request,
        MessageBusInterface $bus,
        ApplicationTracesLogger $logger,
        UnitRepository $unitRepository,
        EventDispatcherInterface $dispatcher,
    ): JsonResponse {
        $appointmentTypeMessage = ApplicationTracesMessage::APPOINTMENT_VALIDATION_MANAGEMENT;

        $isUpdate = false;
        if ($complaint->getAppointmentTime() && $complaint->getAppointmentDate()) {
            $appointmentTypeMessage = ApplicationTracesMessage::APPOINTMENT_CHANGE_MANAGEMENT;
            $complaint->incrementAppointmentCancellationCounter();
            $isUpdate = true;
        }

        $form = $this->createForm(AppointmentType::class, $complaint);
        $form->handleRequest($request);

        /** @var User $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $complaintRepository->save($complaint, true);
            $logger->log(ApplicationTracesMessage::message(
                $appointmentTypeMessage,
                $complaint->getDeclarationNumber(),
                $user->getNumber(),
                $request->getClientIp()
            ));
            /** @var string $unitCode */
            $unitCode = $complaint->getUnitToReassign();
            $unit = $unitRepository->findOneBy(['code' => $unitCode]);

            $dispatcher->addListener(KernelEvents::TERMINATE, function () use ($complaint, $isUpdate, $bus) {
                $bus->dispatch(new AppointmentMessage((int) $complaint->getId(), $isUpdate));
            });

            $bus->dispatch(new InfocentreMessage($appointmentTypeMessage, $complaint, $unit));

            return new JsonResponse();
        }

        return $this->json([
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
