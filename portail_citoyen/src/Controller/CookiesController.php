<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CookiesController extends AbstractController
{
    #[Route('/infos/gestion-des-cookies', name: 'cookies')]
    public function __invoke(): Response
    {
        return $this->render('pages/cookies.html.twig');
    }
}
