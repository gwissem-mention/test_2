<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Comment;
use App\Entity\Complaint;
use App\Entity\User;
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
                $form = $complaint->isUnitReassignmentAsked() ?
                    $this->renderView(
                        'pages/complaint/forms/unit_reassignment_validation_form.html.twig',
                        [
                            'unitReassignForm' => $form->createView(),
                            'complaint' => $complaint,
                        ]
                    ) :
                    $this->renderView(
                        'common/_form.html.twig',
                        ['form' => $form->createView()]
                    );

                return $this->json([
                    'form' => $form,
                ], 422);
            }

            /** @var string $unitCodeToReassign */
            $unitCodeToReassign = $complaint->getUnitToReassign();
            $unitToReassign = $unitRepository->findOneBy(['code' => $unitCodeToReassign]);

            if ($this->isGranted('ROLE_SUPERVISOR')) {
                /** @var User $user */
                $user = $this->getUser();

                $unitReassignmentReason = (new Comment())
                    ->setAuthor($user)
                    ->setTitle(Comment::UNIT_REASSIGNMENT_REASON)
                    ->setContent($complaint->getUnitReassignText() ?? '')
                ;

                $complaint
                    ->setUnitAssigned($unitCodeToReassign)
                    ->setUnitToReassign(null)
                    ->setUnitReassignText(null)
                    ->setUnitReassignmentAsked(false)
                    ->setAssignedTo(null)
                    ->addComment($unitReassignmentReason)
                ;

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

                $complaintRepository->save(
                    $complaint
                        ->setStatus(Complaint::STATUS_UNIT_REASSIGNMENT_PENDING)
                        ->setUnitReassignmentAsked(true)
                );

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
                    'unit_name' => $unitToReassign?->getName(),
                ]
            );
        }

        return $this->json([], 422);
    }
}
