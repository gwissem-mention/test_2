<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrivacyController extends AbstractController
{
    #[Route('/infos/donnees-personnelles', name: 'privacy')]
    public function __invoke(): Response
    {
        return $this->render('pages/privacy.html.twig');
    }
}
