<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/porter-plainte')]
class ComplaintController extends AbstractController
{
    #[Route(path: '/', name: 'app_complaint', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('pages/complaint.html.twig');
    }
}
