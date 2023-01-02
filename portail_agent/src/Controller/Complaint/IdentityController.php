<?php

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\IdentityType;
use App\Form\Complaint\RejectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IdentityController extends AbstractController
{
    #[Route(path: '/plainte/identite/{id}', name: 'complaint_identity', methods: ['GET'])]
    public function __invoke(Complaint $complaint): Response
    {
        return $this->render('pages/complaint/identity.html.twig', [
            'complaint' => $complaint,
            'reject_form' => $this->createForm(RejectType::class, $complaint),
            'identity_form' => $this->createForm(IdentityType::class, $complaint->getIdentity(), [
                'is_optin_notification' => $complaint->isOptinNotification(),
            ]),
        ]);
    }
}
