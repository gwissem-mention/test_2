<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Complaint\ComplaintReassignementer;
use App\Complaint\ComplaintWorkflowException;
use App\Entity\Complaint;
use App\Form\Complaint\UnitReassignType;
use App\Referential\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UnitReassignController extends AbstractController
{
    /**
     * @throws ComplaintWorkflowException
     */
    #[IsGranted('COMPLAINT_VIEW', subject: 'complaint')]
    #[Route(path: '/plainte/reorienter/{id}', name: 'complaint_unit_reassign', methods: ['POST'])]
    public function __invoke(
        Complaint $complaint,
        UnitRepository $unitRepository,
        ComplaintReassignementer $complaintReassignementer,
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $form = $this->createForm(UnitReassignType::class, $complaint);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (false === $form->isValid()) {
                $form = $complaint->isUnitReassignmentAsked() ?
                    $this->renderView(
                        'pages/complaint/forms/unit_reassignment_validation_form.html.twig',
                        [
                            'unitReassignForm' => $form->createView(),
                            'complaint' => $complaint,
                        ]
                    ) :
                    $this->renderView(
                        'common/_form.html.twig',
                        ['form' => $form->createView()]
                    );

                return $this->json([
                    'form' => $form,
                ], 422);
            }

            /** @var string $unitCodeToReassign */
            $unitCodeToReassign = $complaint->getUnitToReassign();
            $unitToReassign = $unitRepository->findOneBy(['code' => $unitCodeToReassign]);

            if ($this->isGranted('ROLE_SUPERVISOR')) {
                $complaintReassignementer->reassignAsSupervisor($complaint, $unitCodeToReassign, (string) $complaint->getUnitReassignText());
            } else {
                $complaintReassignementer->askReassignement($complaint, $unitCodeToReassign, (string) $complaint->getUnitReassignText());
            }

            $entityManager->flush();

            return $this->json(
                [
                    'unit_name' => $unitToReassign?->getName(),
                ]
            );
        }

        return $this->json([], 422);
    }
}
