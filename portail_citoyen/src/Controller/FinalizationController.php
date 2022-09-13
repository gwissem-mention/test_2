<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FinalizationController extends AbstractController
{
    #[Route('/declaration/finalisation', name: 'finalization')]
    public function __invoke(): Response
    {
        return $this->render('finalization.html.twig');
    }
}
