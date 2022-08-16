<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ConsentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitorAgreementController extends AbstractController
{
    #[Route('/visitor-agreement', name: 'visitor_agreement')]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ConsentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('complaint_identity');
        }

        return $this->render('visitor_agreement.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
