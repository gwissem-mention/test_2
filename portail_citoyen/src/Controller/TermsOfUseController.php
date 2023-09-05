<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TermsOfUseController extends AbstractController
{
    #[Route('/conditions-generales-dutilisation', name: 'terms_use')]
    public function __invoke(): Response
    {
        return $this->render('pages/terms_use.html.twig');
    }
}
