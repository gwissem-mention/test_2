<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeUnfoldingController extends AbstractController
{
    #[Route('/accueil-deroule', name: 'home_unfolding')]
    public function __invoke(): Response
    {
        return $this->render('pages/index_unfolding.html.twig');
    }
}
