<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FaqController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/faq', name: 'faq', methods: ['GET'])]
    public function __invoke(QuestionRepository $questionRepository): Response
    {
        return $this->render('pages/faq.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }
}
