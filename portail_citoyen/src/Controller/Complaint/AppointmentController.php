<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    #[Route('/declaration/rendez-vous', name: 'appointment')]
    public function __invoke(): Response
    {
        return $this->render('complaint/appointment.html.twig');
    }
}
