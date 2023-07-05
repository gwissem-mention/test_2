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

        if (true === $request->query->getBoolean('my_complaints_reports')) {
            return $this->redirectToRoute('my_complaints_reports');
        }

        if ($request->query->has('france_connected')) {
            if (true === $request->query->getBoolean('france_connected')) {
                $franceConnectHandler->setIdentityToComplaint();

                if (true === $request->query->getBoolean('identity')) {
                    return $this->redirectToRoute('complaint_identity');
                }
            } elseif (false === $request->query->getBoolean('france_connected')) {
                $franceConnectHandler->clear();
            }

            return $this->redirectToRoute('complaint_law_refresher');
        }

        return $this->render('pages/authentication.html.twig');
    }
}
