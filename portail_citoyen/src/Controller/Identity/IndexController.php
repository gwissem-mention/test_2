<?php

declare(strict_types=1);

namespace App\Controller\Identity;

use App\Form\Identity\IdentityType;
use App\FranceConnect\IdentitySessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/identite', name: 'identity')]
class IndexController extends AbstractController
{
    public function __invoke(Request $request, IdentitySessionHandler $fcIdentitySessionHandler): Response
    {
        if ($request->query->has('france_connected')) {
            $fcIdentitySessionHandler->setIdentity(
                'Michel',
                'DUPONT',
                '1967-03-02',
                'male',
                '75056',
                '75056',
                'michel.dupont@example.com'
            );
        } else {
            $fcIdentitySessionHandler->removeIdentity();
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
