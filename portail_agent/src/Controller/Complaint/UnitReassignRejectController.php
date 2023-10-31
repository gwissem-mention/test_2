<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

// Hidden for experimentation PEL-1590

// use App\Complaint\ComplaintWorkflowException;
// use App\Complaint\ComplaintWorkflowManager;
// use App\Entity\Comment;
// use App\Entity\Complaint;
// use App\Entity\User;
// use App\Factory\NotificationFactory;
// use App\Form\Complaint\UnitReassignType;
// use App\Logger\ApplicationTracesLogger;
// use App\Logger\ApplicationTracesMessage;
// use App\Repository\ComplaintRepository;
// use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Security\Http\Attribute\IsGranted;

class UnitReassignRejectController extends AbstractController
{
    //    /**
    //     * @throws ComplaintWorkflowException
    //     */
    //    #[IsGranted('ROLE_SUPERVISOR')]
    //    #[IsGranted('COMPLAINT_VIEW', subject: 'complaint')]
    //    #[Route(path: '/plainte/refuser-reorientation/{id}', name: 'complaint_unit_reassign_reject', methods: ['POST'])]
    //    public function __invoke(
    //        Complaint $complaint,
    //        ComplaintRepository $complaintRepository,
    //        NotificationFactory $notificationFactory,
    //        UserRepository $userRepository,
    //        Request $request,
    //        ComplaintWorkflowManager $complaintWorkflowManager,
    //        ApplicationTracesLogger $logger
    //    ): JsonResponse {
    //        $form = $this->createForm(UnitReassignType::class, $complaint);
    //        $form->handleRequest($request);
    //
    //        if (false === $form->isValid()) {
    //            return $this->json([
    //                'form' => $this->renderView(
    //                    'pages/complaint/forms/unit_reassignment_validation_form.html.twig',
    //                    [
    //                        'unitReassignForm' => $form->createView(),
    //                        'complaint' => $complaint,
    //                    ]
    //                ),
    //            ], 422);
    //        }
    //
    //        /** @var User $user */
    //        $user = $this->getUser();
    //
    //        $unitReassignmentRejectReason = (new Comment())
    //            ->setAuthor($user)
    //            ->setTitle(Comment::UNIT_REASSIGNMENT_REJECT_REASON)
    //            ->setContent($complaint->getUnitReassignText() ?? '');
    //
    //        $complaintWorkflowManager->rejectUnitRedirection($complaint);
    //
    //        $complaintRepository->save(
    //            $complaint
    //                ->setUnitToReassign(null)
    //                ->setUnitReassignText(null)
    //                ->setUnitReassignmentAsked(false)
    //                ->addComment($unitReassignmentRejectReason), true
    //        );
    //        $logger->log(ApplicationTracesMessage::message(
    //            ApplicationTracesMessage::REJECT,
    //            $complaint->getDeclarationNumber(),
    //            $user->getNumber(),
    //            $request->getClientIp()
    //        ));
    //
    //        if ($complaint->getAssignedTo() instanceof User) {
    //            $userRepository->save(
    //                $complaint->getAssignedTo()->addNotification(
    //                    $notificationFactory->createForComplaintReassignmentRefused($complaint, $user)
    //                ),
    //                true
    //            );
    //        }
    //
    //        return new JsonResponse();
    //    }
}
