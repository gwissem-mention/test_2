<?php

declare(strict_types=1);

namespace App\Controller\Identity;

use App\Form\Identity\IdentityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/identite', name: 'identity')]
class IndexController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $session = $request->getSession();
        if ($request->query->has('france_connected')) {
            $session->set('france_connected', true);
        } else {
            $session->remove('france_connected');
        }

        $form = $this->createForm(IdentityType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('facts');
        }

        return $this->render('identity/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
