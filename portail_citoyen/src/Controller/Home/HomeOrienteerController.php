<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeOrienteerController extends AbstractController
{
    #[Route('/accueil-orienteur', name: 'home_orienteer')]
    public function __invoke(): Response
    {
        return $this->render('home/index_orienteer.html.twig');
    }
}
