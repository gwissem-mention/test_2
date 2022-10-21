<?php

declare(strict_types=1);

namespace App\Controller;

use App\Session\ComplaintSessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    #[Route('/authentification', name: 'authentication')]
    public function __invoke(Request $request, ComplaintSessionHandler $complaintSessionHandler): Response
    {
        $complaintSessionHandler->init();

        return $this->render('authentication.html.twig');
    }
}
