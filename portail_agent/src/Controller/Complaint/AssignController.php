<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\AssignType;
use App\Referential\Repository\AgentRepository;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AssignController extends AbstractController
{
    #[Route(path: '/plainte/attribuer/{id}', name: 'complaint_assign', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
        AgentRepository $agentRepository,
        Request $request
    ): JsonResponse {
        $form = $this->createForm(AssignType::class, $complaint);
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

            $complaintRepository->save($complaint, true);

            return $this->json(
                ['success' => true, 'agent_name' => $agentRepository->find($complaint->getAssignedTo())?->getName()]
            );
        }

        return $this->json([
            'success' => false,
        ], 422);
    }
}
