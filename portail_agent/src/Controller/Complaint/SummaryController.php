<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\AssignType;
use App\Form\Complaint\RejectType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SummaryController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/recapitulatif/{id}', name: 'complaint_summary', methods: ['GET'])]
    public function __invoke(Complaint $complaint, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('COMPLAINT_VIEW', $complaint);

        return $this->render('pages/complaint/summary.html.twig', [
            'complaint' => $complaint,
            'reject_form' => $this->createForm(RejectType::class, $complaint),
            'assign_form' => $this->createForm(AssignType::class, $complaint),
        ]);
    }
}
