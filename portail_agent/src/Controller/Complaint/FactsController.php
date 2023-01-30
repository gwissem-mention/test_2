<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Entity\Complaint;
use App\Form\Complaint\AssignType;
use App\Form\Complaint\FactsType;
use App\Form\Complaint\RejectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FactsController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/plainte/faits/{id}', name: 'complaint_facts', methods: ['GET'])]
    public function __invoke(Complaint $complaint): Response
    {
        return $this->render('pages/complaint/facts.html.twig', [
            'complaint' => $complaint,
            'reject_form' => $this->createForm(RejectType::class, $complaint),
            'facts_form' => $this->createForm(FactsType::class, $complaint->getFacts()),
            'assign_form' => $this->createForm(AssignType::class, $complaint),
        ]);
    }
}