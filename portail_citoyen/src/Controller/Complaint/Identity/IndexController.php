<?php

declare(strict_types=1);

namespace App\Controller\Complaint\Identity;

use App\Form\Complaint\IdentityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/declaration/identite', name: 'complaint_identity')]
class IndexController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(IdentityType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('complaint_facts');
        }

        return $this->render('complaint/identity/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
