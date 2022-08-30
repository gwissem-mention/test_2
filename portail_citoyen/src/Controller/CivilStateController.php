<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\CivilStateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CivilStateController extends AbstractController
{
    #[Route('/etat-civil', name: 'civil_state')]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(CivilStateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return new Response('The form is valid');
        }

        return $this->render('civil_state.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
