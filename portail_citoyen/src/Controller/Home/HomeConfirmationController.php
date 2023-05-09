<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeConfirmationController extends AbstractController
{
    #[Route('/accueil-confirmation', name: 'home_confirmation')]
    public function __invoke(): Response
    {
        return $this->render('pages/index_confirmation.html.twig');
    }
}
