<?php

declare(strict_types=1);

namespace App\Controller\SSOLess;

use App\Entity\User;
use App\Logger\ApplicationTracesLogger;
use App\Logger\ApplicationTracesMessage;
use App\Security\SSOLessInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CloseSessionController extends AbstractController
{
    #[Route(path: '/sso-less/close-session', name: 'app_sso_less_close_session', condition: "env('ENABLE_SSO') === 'false'")]
    public function __invoke(Request $request, ApplicationTracesLogger $logger): Response
    {
        $response = $this->redirectToRoute(SSOLessInterface::START_SESSION_ROUTE);
        $response->headers->clearCookie(SSOLessInterface::COOKIE_NAME);

        /** @var User $user */
        $user = $this->getUser();

        $logger->log(ApplicationTracesMessage::message(
            ApplicationTracesMessage::LOGOUT,
            null,
            $user->getNumber(),
            $request->getClientIp()
        ));

        return $response;
    }
}
