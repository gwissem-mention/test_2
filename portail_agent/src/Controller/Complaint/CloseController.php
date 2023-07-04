<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Complaint\ComplaintWorkflowException;
use App\Complaint\ComplaintWorkflowManager;
use App\Entity\Complaint;
use App\Form\Complaint\SendReportType;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CloseController extends AbstractController
{
    /**
     * @throws ComplaintWorkflowException
     */
    #[IsGranted('COMPLAINT_VIEW', subject: 'complaint')]
    #[Route(path: '/plainte/fermer/{id}', name: 'complaint_close', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
        Request $request,
        ComplaintWorkflowManager $complaintWorkflowManager,
    ): JsonResponse {
        $form = $this->createForm(SendReportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $complaintWorkflowManager->closeAfterAppointment($complaint);
            $complaintRepository->save($complaint, true);

            return new JsonResponse();
        }

        return $this->json([
            'form' => $this->renderView(
                'common/_form.html.twig',
                ['form' => $form->createView()]
            ),
        ], 422);
    }
}
