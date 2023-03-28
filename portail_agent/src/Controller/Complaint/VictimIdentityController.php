<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\AssignType;
use App\Form\Complaint\CorporationType;
use App\Form\Complaint\IdentityType;
use App\Form\Complaint\RejectType;
use App\Form\Complaint\UnitReassignType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class VictimIdentityController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/victime/{id}', name: 'complaint_victim_identity', methods: ['GET'])]
    public function __invoke(Complaint $complaint): Response
    {
        $template = $complaint->getPersonLegalRepresented() ? 'pages/complaint/person_legal_victim_identity.html.twig' : 'pages/complaint/corporation_victim_identity.html.twig';
        $form = $complaint->getPersonLegalRepresented() ? $this->createForm(IdentityType::class, $complaint->getPersonLegalRepresented()) : $this->createForm(CorporationType::class, $complaint->getCorporationRepresented());

        return $this->render($template, [
            'complaint' => $complaint,
            'reject_form' => $this->createForm(RejectType::class, $complaint),
            'victim_identity_form' => $form,
            'assign_form' => $this->createForm(AssignType::class, $complaint),
            'unit_reassign_form' => $this->createForm(UnitReassignType::class, $complaint),
        ]);
    }
}
