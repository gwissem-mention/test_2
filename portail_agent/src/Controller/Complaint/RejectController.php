<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\RejectType;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RejectController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/rejeter/{id}', name: 'complaint_reject', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
        Request $request
    ): JsonResponse {
        $form = $this->createForm(RejectType::class, $complaint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $complaintRepository->save($complaint->setStatus(Complaint::STATUS_CLOSED), true);

            return $this->json(['success' => true]);
        }

        return $this->json([
            'success' => false,
            'form' => $this->renderView(
                'common/_form.html.twig',
                ['form' => $form->createView()]
            ),
        ], 422);
    }
}
