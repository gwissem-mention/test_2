<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PocController extends AbstractController
{
    #[Route(path: '/poc', name: 'app_poc', methods: ['GET'])]
    public function pocController(): Response
    {
        return $this->render('poc/accordion.html.twig');
    }
}
