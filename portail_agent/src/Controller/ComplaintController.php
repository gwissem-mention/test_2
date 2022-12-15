<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComplaintController extends AbstractController
{
    #[Route('/plainte/{id}', name: 'complaint')]
    public function __invoke(string $id): Response
    {
        return $this->render('complaint.html.twig', [
            'id' => $id,
        ]);
    }
}
