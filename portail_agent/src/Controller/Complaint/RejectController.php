<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\RejectType;
use App\Repository\ComplaintRepository;
use App\Salesforce\Messenger\ComplaintRejection\ComplaintRejectionMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RejectController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/rejeter/{id}', name: 'complaint_reject', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
        Request $request,
        MessageBusInterface $bus
    ): JsonResponse {
        $form = $this->createForm(RejectType::class, $complaint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $complaintRepository->save($complaint->setStatus(Complaint::STATUS_REJECTED), true);

            $bus->dispatch(new ComplaintRejectionMessage((int) $complaint->getId())); // Salesforce email

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
