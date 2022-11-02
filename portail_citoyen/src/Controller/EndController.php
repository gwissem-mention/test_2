<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EndController extends AbstractController
{
    #[Route('/fin', name: 'end')]
    public function __invoke(): Response
    {
        return $this->render('pages/end.html.twig');
    }
}
