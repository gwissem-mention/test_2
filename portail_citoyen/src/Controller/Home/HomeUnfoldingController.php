<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Form\ConsentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeUnfoldingController extends AbstractController
{
    #[Route('/accueil-deroule', name: 'home_unfolding')]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ConsentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('authentication');
        }

        return $this->render('pages/index_unfolding.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
