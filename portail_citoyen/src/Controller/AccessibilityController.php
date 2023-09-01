<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccessibilityController extends AbstractController
{
    #[Route('/infos/accessibilite', name: 'accessibility')]
    public function __invoke(): Response
    {
        return $this->render('pages/accessibility.html.twig');
    }
}
