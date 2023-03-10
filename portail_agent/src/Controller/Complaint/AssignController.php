<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Form\Complaint\AssignType;
use App\Repository\ComplaintRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AssignController extends AbstractController
{
    #[IsGranted('ROLE_SUPERVISOR')]
    #[Route(path: '/plainte/attribuer/{id}', name: 'complaint_assign', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        ComplaintRepository $complaintRepository,
        UserRepository $userRepository,
        NotificationFactory $notificationFactory,
        Request $request
    ): JsonResponse {
        $reassignment = $complaint->getAssignedTo() instanceof User;
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

            $complaintRepository->save($complaint->setStatus(Complaint::STATUS_ASSIGNED));

            /** @var User $user */
            $user = $complaint->getAssignedTo();

            $userRepository->save(
                $user->addNotification($notificationFactory->createForComplaintAssigned($complaint, $reassignment)), true
            );

            return $this->json(
                [
                    'success' => true,
                    'agent_name' => $user->getAppellation(),
                ]
            );
        }

        return $this->json([
            'success' => false,
        ], 422);
    }
}
