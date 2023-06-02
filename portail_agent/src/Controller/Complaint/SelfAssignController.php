<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Complaint\ComplaintAssignementer;
use App\Entity\Complaint;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SelfAssignController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/auto-attribuer/{id}', name: 'complaint_self_assign', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintAssignementer $complaintAssignementer,
    ): JsonResponse {
        $this->denyAccessUnlessGranted('COMPLAINT_VIEW', $complaint);

        /** @var User $user */
        $user = $this->getUser();

        $complaintAssignementer->assignOneTo($complaint, $user, false);

        return new JsonResponse();
    }
}
