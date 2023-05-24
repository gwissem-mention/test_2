<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Comment;
use App\Entity\Complaint;
use App\Entity\User;
use App\Form\Complaint\UnitReassignType;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UnitReassignRejectController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/refuser-reorientation/{id}', name: 'complaint_unit_reassign_reject', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
        Request $request
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_SUPERVISOR');

        $form = $this->createForm(UnitReassignType::class, $complaint);
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->json([
                'form' => $this->renderView(
                    'pages/complaint/forms/unit_reassignment_validation_form.html.twig',
                    [
                        'unitReassignForm' => $form->createView(),
                        'complaint' => $complaint,
                    ]
                ),
            ], 422);
        }

        /** @var User $user */
        $user = $this->getUser();

        $unitReassignmentRejectReason = (new Comment())
            ->setAuthor($user)
            ->setTitle(Comment::UNIT_REASSIGNMENT_REJECT_REASON)
            ->setContent($complaint->getUnitReassignText() ?? '')
        ;

        $complaintRepository->save(
            $complaint
                ->setStatus(Complaint::STATUS_ASSIGNED)
                ->setUnitToReassign(null)
                ->setUnitReassignText(null)
                ->setUnitReassignmentAsked(false)
                ->addComment($unitReassignmentRejectReason), true
        );

        return new JsonResponse();
    }
}
