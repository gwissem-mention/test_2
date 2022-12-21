<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Complaint;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function __invoke(ComplaintRepository $complaintRepository): Response
    {
        return $this->render('pages/index.html.twig', [
            'complaints' => $complaintRepository->findBy(['status' => Complaint::STATUS_ONGOING]),
        ]);
    }
}
