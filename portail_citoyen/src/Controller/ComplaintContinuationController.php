<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComplaintContinuationController extends AbstractController
{
    #[Route('/poursuivre', name: 'complaint_continuation')]
    public function __invoke(): Response
    {
        return $this->render('complaint_continuation.html.twig');
    }
}
