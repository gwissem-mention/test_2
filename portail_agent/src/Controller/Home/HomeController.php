<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Form\Complaint\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/', name: 'home')]
    public function __invoke(): Response
    {
        return $this->render('pages/home/index.html.twig', [
            'search_form' => $this->createForm(SearchType::class),
        ]);
    }
}
