<?php

declare(strict_types=1);

namespace App\Controller\Complaint\Identity;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/declaration/identite', name: 'complaint_identity')]
class IndexController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('complaint/identity/index.html.twig');
    }
}
