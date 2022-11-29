<?php

declare(strict_types=1);

namespace App\Controller;

use App\Session\FranceConnectHandler;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/porter-plainte', name: 'complaint', methods: ['GET'])]
class ComplaintController extends AbstractController
{
    public function __invoke(
        Request $request,
        FranceConnectHandler $franceConnectHandler,
        SessionHandler $sessionHandler
    ): Response {
        $sessionHandler->init();

        return $this->render('pages/complaint.html.twig');
    }
}
