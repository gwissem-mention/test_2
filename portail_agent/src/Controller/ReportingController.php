<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReportingController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/reporting', name: 'reporting')]
    public function __invoke(): Response
    {
        return $this->render('pages/reporting.html.twig');
    }
}
