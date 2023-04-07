<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\AssignType;
use App\Form\Complaint\IdentityType;
use App\Form\Complaint\RejectType;
use App\Form\Complaint\UnitReassignType;
use App\Form\DropZoneType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IdentityController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/identite/{id}', name: 'complaint_identity', methods: ['GET'])]
    public function __invoke(Complaint $complaint): Response
    {
        $this->denyAccessUnlessGranted('COMPLAINT_VIEW', $complaint);

        return $this->render('pages/complaint/identity.html.twig', [
            'complaint' => $complaint,
            'reject_form' => $this->createForm(RejectType::class, $complaint),
            'identity_form' => $this->createForm(IdentityType::class, $complaint->getIdentity(), [
                'is_optin_notification' => $complaint->isOptinNotification(),
            ]),
            'assign_form' => $this->createForm(AssignType::class, $complaint),
            'unit_reassign_form' => $this->createForm(UnitReassignType::class, $complaint),
            'drag_drop_form' => $this->createForm(DropZoneType::class),
        ]);
    }
}
