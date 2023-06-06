<?php

declare(strict_types=1);

namespace App\Controller\Help;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TermsOfServiceController extends AbstractController
{
    #[Route('/cgu', name: 'cgu')]
    public function __invoke(): Response
    {
        return $this->render('pages/terms_of_service.html.twig');
    }
}
