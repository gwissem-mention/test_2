<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Complaint\ComplaintAssignementer;
use App\Complaint\ComplaintWorkflowException;
use App\Entity\Complaint;
use App\Entity\User;
use App\Form\Complaint\AssignType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AssignController extends AbstractController
{
    /**
     * @throws ComplaintWorkflowException
     */
    #[IsGranted('ROLE_SUPERVISOR')]
    #[IsGranted('COMPLAINT_VIEW', subject: 'complaint')]
    #[Route(path: '/plainte/attribuer/{id}', name: 'complaint_assign', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        Request $request,
        ComplaintAssignementer $complaintAssignementer,
    ): JsonResponse {
        $reassignment = $complaint->getAssignedTo() instanceof User;
        $form = $this->createForm(AssignType::class, $complaint);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if (false === $form->isValid()) {
                return $this->json([
                    'form' => $this->renderView(
                        'common/_form.html.twig',
                        ['form' => $form->createView()]
                    ),
                ], 422);
            }

            $user = $complaint->getAssignedTo();

            if (null !== $user) {
                $complaintAssignementer->assignOneTo($complaint, $user, $reassignment, false);
            }

            return $this->json(
                [
                    'agent_name' => $user?->getAppellation(),
                ]
            );
        }

        return $this->json([], 422);
    }
}
