<?php

declare(strict_types=1);

namespace App\Controller\Complaint\OffenseNature;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/declaration/nature-infraction', name: 'complaint_offense_nature')]
class IndexController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('complaint/offense_nature/index.html.twig');
    }
}
