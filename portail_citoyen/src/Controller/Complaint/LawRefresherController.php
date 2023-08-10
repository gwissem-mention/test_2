<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LawRefresherController extends AbstractController
{
    #[Route('/porter-plainte/rappel-a-la-loi', name: 'complaint_law_refresher')]
    public function __invoke(
        Request $request,
        SessionHandler $sessionHandler
    ): Response {
        $sessionHandler->init();

        return $this->render('pages/complaint_law_refresher.html.twig', ['complaint' => $sessionHandler->getComplaint()]);
    }
}
