<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/', name: 'home')]
    public function __invoke(): Response
    {
        return $this->render('pages/home/index.html.twig');
    }
}
