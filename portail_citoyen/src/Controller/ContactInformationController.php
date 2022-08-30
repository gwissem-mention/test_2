<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ContactInformationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactInformationController extends AbstractController
{
    #[Route('/coordonnees', name: 'contact_information')]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ContactInformationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return new Response('The form is valid');
        }

        return $this->render('contact_information.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
