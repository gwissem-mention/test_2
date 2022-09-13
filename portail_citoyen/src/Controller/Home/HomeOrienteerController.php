<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Form\ConsentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeOrienteerController extends AbstractController
{
    #[Route('/accueil-orienteur', name: 'home_orienteer')]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ConsentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('authentication');
        }

        return $this->render('home/index_orienteer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
