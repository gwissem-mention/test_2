<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeLawRefresher extends AbstractController
{
    #[Route('/rappel-a-la-loi', name: 'home_law_refresher')]
    public function __invoke(): Response
    {
        return $this->render('pages/index_law_refresher.html.twig');
    }
}
