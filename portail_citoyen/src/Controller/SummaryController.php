<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SummaryController extends AbstractController
{
    #[Route('/recapitulatif', name: 'summary')]
    public function __invoke(): Response
    {
        return $this->render('pages/summary.html.twig');
    }
}
