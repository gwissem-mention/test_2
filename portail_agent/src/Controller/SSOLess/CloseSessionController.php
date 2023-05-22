<?php

declare(strict_types=1);

namespace App\Controller\SSOLess;

use App\Security\SSOLessInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CloseSessionController extends AbstractController
{
    #[Route(path: '/sso-less/close-session', name: 'app_sso_less_close_session', condition: "env('ENABLE_SSO') === 'false'")]
    public function __invoke(Request $request): Response
    {
        $response = $this->redirectToRoute(SSOLessInterface::START_SESSION_ROUTE);
        $response->headers->clearCookie(SSOLessInterface::COOKIE_NAME);

        return $response;
    }
}
