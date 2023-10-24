<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Complaint\CommentHandler;
use App\Complaint\ComplaintWorkflowException;
use App\Complaint\ComplaintWorkflowManager;
use App\Entity\Complaint;
use App\Entity\User;
use App\Form\Complaint\RejectType;
use App\Logger\ApplicationTracesLogger;
use App\Logger\ApplicationTracesMessage;
use App\Messenger\InformationCenter\InfocentreMessage;
use App\Referential\Repository\UnitRepository;
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
    /**
     * @throws ComplaintWorkflowException
     */
    #[IsGranted('COMPLAINT_VIEW', subject: 'complaint')]
    #[Route(path: '/plainte/rejeter/{id}', name: 'complaint_reject', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
        Request $request,
        MessageBusInterface $bus,
        ComplaintWorkflowManager $complaintWorkflowManager,
        ApplicationTracesLogger $logger,
        UnitRepository $unitRepository,
        CommentHandler $commentHandler,
    ): JsonResponse {
        $form = $this->createForm(RejectType::class, $complaint);
        $form->handleRequest($request);

        /** @var User $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $complaintWorkflowManager->reject($complaint);

            $commentHandler->addRefusalReason($complaint);

            $complaintRepository->save($complaint->setRejectedAt(new \DateTimeImmutable()), true);

            $logger->log(ApplicationTracesMessage::message(
                ApplicationTracesMessage::REJECT,
                $complaint->getDeclarationNumber(),
                $user->getNumber(),
                $request->getClientIp()
            ));
            /** @var string $unitCode */
            $unitCode = $complaint->getUnitToReassign();
            $unit = $unitRepository->findOneBy(['code' => $unitCode]);

            $bus->dispatch(new InfocentreMessage(ApplicationTracesMessage::REJECT, $complaint, $unit));
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
