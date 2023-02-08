<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/porter-plainte/identite', name: 'complaint_identity', methods: ['GET'])]
class IdentityController extends AbstractController
{
    public function __invoke(
        Request $request,
        SessionHandler $sessionHandler,
    ): Response {
        $sessionHandler->init();

        return $this->render('pages/complaint_identity.html.twig', ['complaint' => $sessionHandler->getComplaint()]);
    }
}
