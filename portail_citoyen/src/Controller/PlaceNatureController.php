<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\PlaceNatureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlaceNatureController extends AbstractController
{
    #[Route('/place/nature', name: 'place_nature')]
    public function __invoke(): Response
    {
        $form = $this->createForm(PlaceNatureType::class);

        return $this->render('place_nature.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
