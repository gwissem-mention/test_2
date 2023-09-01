<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    #[Route('/infos/mentions-legales', name: 'legal')]
    public function __invoke(): Response
    {
        return $this->render('pages/legal.html.twig');
    }
}
