<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UnitReassignRejectController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/refuser-reorientation/{id}', name: 'complaint_unit_reassign_reject', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_SUPERVISOR');

        $complaintRepository->save(
            $complaint
                ->setStatus(Complaint::STATUS_ASSIGNED)
                ->setUnitToReassign(null)
                ->setUnitReassignText(null)
                ->setUnitReassignmentAsked(false), true
        );

        return new JsonResponse();
    }
}
