<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Factory\NotificationFactory;
use App\Form\Complaint\UnitReassignType;
use App\Referential\Repository\UnitRepository;
use App\Repository\ComplaintRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UnitReassignController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/reorienter/{id}', name: 'complaint_unit_reassign', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
        UserRepository $userRepository,
        UnitRepository $unitRepository,
        NotificationFactory $notificationFactory,
        Request $request
    ): JsonResponse {
        $form = $this->createForm(UnitReassignType::class, $complaint);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (false === $form->isValid()) {
                return $this->json([
                    'success' => false,
                    'form' => $this->renderView(
                        'common/_form.html.twig',
                        ['form' => $form->createView()]
                    ),
                ], 422);
            }

            /** @var string $unitCodeToReassign */
            $unitCodeToReassign = $complaint->getUnitToReassign();
            $unitToReassign = $unitRepository->findOneBy(['code' => $unitCodeToReassign]);

            if ($this->isGranted('ROLE_SUPERVISOR')) {
                $complaint
                    ->setUnitAssigned($unitCodeToReassign)
                    ->setUnitToReassign(null)
                    ->setUnitReassignText(null)
                    ->setAssignedTo(null);

                /** @var string $unitCode */
                $unitCode = $complaint->getUnitAssigned();

                $complaintRepository->save($complaint->setStatus(Complaint::STATUS_ASSIGNMENT_PENDING), true);

                $supervisors = $userRepository->getSupervisorsByUnit($unitCode);

                foreach ($supervisors as $supervisor) {
                    $userRepository->save(
                        $supervisor->addNotification($notificationFactory->createForComplaintUnitReassignment($complaint)),
                        true
                    );
                }
            } else {
                /** @var string $unitCode */
                $unitCode = $complaint->getUnitAssigned();

                $complaintRepository->save($complaint->setStatus(Complaint::STATUS_UNIT_REASSIGNMENT_PENDING));

                $supervisors = $userRepository->getSupervisorsByUnit($unitCode);

                foreach ($supervisors as $supervisor) {
                    $userRepository->save(
                        $supervisor->addNotification($notificationFactory->createForComplaintUnitReassignmentOrdered($complaint)),
                        true
                    );
                }
            }

            return $this->json(
                [
                    'success' => true,
                    'unit_name' => $unitToReassign?->getName(),
                ]
            );
        }

        return $this->json([
            'success' => false,
        ], 422);
    }
}