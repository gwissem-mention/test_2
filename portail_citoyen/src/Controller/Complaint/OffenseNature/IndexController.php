<?php

declare(strict_types=1);

namespace App\Controller\Complaint\OffenseNature;

use App\Form\Complaint\Infraction\OffenseNatureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/declaration/nature-infraction', name: 'complaint_offense_nature')]
class IndexController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(OffenseNatureType::class);
        $form->handleRequest($request);

        return $this->render('complaint/offense_nature/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
