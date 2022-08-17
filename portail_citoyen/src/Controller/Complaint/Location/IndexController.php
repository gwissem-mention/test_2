<?php

declare(strict_types=1);

namespace App\Controller\Complaint\Location;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/declaration/lieu', name: 'complaint_location')]
class IndexController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('complaint/location/index.html.twig');
    }
}
