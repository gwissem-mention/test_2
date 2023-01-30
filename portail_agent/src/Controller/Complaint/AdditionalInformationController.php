<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\AdditionalInformationType;
use App\Form\Complaint\AssignType;
use App\Form\Complaint\RejectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdditionalInformationController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/informations-complementaires/{id}', name: 'complaint_additional_information', methods: ['GET'])]
    public function __invoke(Complaint $complaint): Response
    {
        return $this->render('pages/complaint/additional_information.html.twig', [
            'complaint' => $complaint,
            'reject_form' => $this->createForm(RejectType::class, $complaint),
            'additional_information_form' => $this->createForm(
                AdditionalInformationType::class,
                $complaint->getAdditionalInformation()
            ),
            'assign_form' => $this->createForm(AssignType::class, $complaint),
        ]);
    }
}