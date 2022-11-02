<?php

declare(strict_types=1);

namespace App\Controller\Facts;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/faits', name: 'facts')]
class IndexController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return $this->render('complaints/pages/facts.html.twig');
    }
}
