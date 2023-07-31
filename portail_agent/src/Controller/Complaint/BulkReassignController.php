<?php

namespace App\Controller\Complaint;

use App\Complaint\ComplaintReassignementer;
use App\Complaint\ComplaintWorkflowException;
use App\Form\Complaint\BulkReassignType;
use App\Form\DTO\BulkReassignAction;
use App\Referential\Repository\UnitRepository;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/plainte/reorienter-en-masse', name: 'complaint_bulk_reassign', methods: ['POST'])]
class BulkReassignController extends AbstractController
{
    public function __construct(
        private readonly ComplaintReassignementer $complaintReassignementer,
        private readonly ComplaintRepository $complaintRepository,
        private readonly UnitRepository $unitRepository,
        private readonly TranslatorInterface $translator
    ) {
    }

    #[IsGranted('ROLE_SUPERVISOR')]
    public function __invoke(Request $request): JsonResponse
    {
        $form = $this->createForm(BulkReassignType::class, new BulkReassignAction())
            ->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                return $this->errorResponse($form);
            }

            /** @var BulkReassignAction $bulkReassignAction */
            $bulkReassignAction = $form->getData();

            $complaints = array_filter(array_map(
                fn ($id) => $this->complaintRepository->find((int) str_replace('complaint_', '', $id)),
                explode(',', $bulkReassignAction->getComplaints() ?? '')
            ));

            if (!is_null($bulkReassignAction->getUnitCodeToReassign()) && !is_null($bulkReassignAction->getReassignText())) {
                try {
                    $this->complaintReassignementer->reassignBulkTo(
                        $complaints,
                        $bulkReassignAction->getUnitCodeToReassign(),
                        $bulkReassignAction->getReassignText()
                    );
                } catch (ComplaintWorkflowException) {
                    $form->addError(new FormError($this->translator->trans('pel.only.assigned.complaint.can.be.reassigned')));

                    return $this->errorResponse($form);
                }
            }

            return $this->json([
                'unit_name' => $this->unitRepository->findOneBy(['code' => $bulkReassignAction->getUnitCodeToReassign()])?->getName(),
            ]);
        }

        return $this->errorResponse();
    }

    private function errorResponse(FormInterface $form = null): JsonResponse
    {
        $response = [];

        if ($form instanceof FormInterface) {
            $response['form'] = $this->renderView('common/_form.html.twig', ['form' => $form->createView()]);
        }

        return $this->json($response, 422);
    }
}
