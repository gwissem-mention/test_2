<?php

declare(strict_types=1);

namespace App\Controller;

use App\Session\FranceConnectHandler;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    #[Route('/authentification', name: 'authentication')]
    public function __invoke(
        Request $request,
        SessionHandler $sessionHandler,
        FranceConnectHandler $franceConnectHandler,
    ): Response {
        $sessionHandler->init();

        if ($request->query->has('france_connected')) {
            if ('1' === $request->query->get('france_connected')) {
                $franceConnectHandler->setIdentityToComplaint();
            } elseif ('0' === $request->query->get('france_connected')) {
                $franceConnectHandler->clear();
            }

            return $this->redirectToRoute('complaint_identity');
        }

        return $this->render('pages/authentication.html.twig', [
            'complaint' => $sessionHandler->getComplaint(),
        ]);
    }
}
