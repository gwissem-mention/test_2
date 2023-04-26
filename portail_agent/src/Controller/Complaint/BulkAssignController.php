<?php

namespace App\Controller\Complaint;

use App\Complaint\ComplaintAssignementer;
use App\Form\Complaint\BulkAssignType;
use App\Form\DTO\BulkAssignAction;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/plainte/attribuer-en-masse', name: 'complaint_bulk_assign', methods: ['POST'])]
class BulkAssignController extends AbstractController
{
    public function __construct(
        private readonly ComplaintAssignementer $complaintAssignementer,
        private readonly ComplaintRepository $complaintRepository,
    ) {
    }

    #[IsGranted('ROLE_SUPERVISOR')]
    public function __invoke(Request $request): JsonResponse
    {
        $form = $this->createForm(BulkAssignType::class, new BulkAssignAction())
            ->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                return $this->errorResponse($form);
            }

            /** @var BulkAssignAction $bulkAssignAction */
            $bulkAssignAction = $form->getData();

            $complaints = array_filter(array_map(
                fn ($id) => $this->complaintRepository->find((int) str_replace('complaint_', '', $id)),
                explode(',', $bulkAssignAction->getComplaints() ?? '')
            ));

            if (!is_null($bulkAssignAction->getAssignedTo())) {
                $this->complaintAssignementer->assignBulkTo($complaints, $bulkAssignAction->getAssignedTo());
            }

            return $this->json([
                'success' => true,
                'agent_name' => $bulkAssignAction->getAssignedTo()?->getAppellation(),
            ]);
        }

        return $this->errorResponse();
    }

    private function errorResponse(FormInterface|null $form = null): JsonResponse
    {
        $response = [
            'success' => false,
        ];

        if ($form instanceof FormInterface) {
            $response['form'] = $this->renderView('common/_form.html.twig', ['form' => $form->createView()]);
        }

        return $this->json($response, 422);
    }
}
