<?php

declare(strict_types=1);

namespace App\Controller\Complaint\OffenseNature;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/declaration/nature-infraction/texte', name: 'complaint_offense_nature_warning_text', methods: ['GET'])]
class WarningTextController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('complaint/offense_nature/warning_text.html.twig');
    }
}
