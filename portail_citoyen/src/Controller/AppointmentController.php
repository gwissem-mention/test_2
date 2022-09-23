<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    #[Route('/rendez-vous', name: 'appointment')]
    public function __invoke(): Response
    {
        return $this->render('appointment.html.twig');
    }
}
