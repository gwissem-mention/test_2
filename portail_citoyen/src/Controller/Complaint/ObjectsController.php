<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Form\Model\Facts\FactsModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/porter-plainte/objets', name: 'complaint_objects', methods: ['GET'])]
class ObjectsController extends AbstractController
{
    public function __invoke(
        Request $request,
        SessionHandler $sessionHandler,
    ): Response {
        if (!$sessionHandler->getComplaint()?->getFacts() instanceof FactsModel) {
            return $this->redirectToRoute('home');
        }

        return $this->render('pages/complaint_objects.html.twig', ['complaint' => $sessionHandler->getComplaint()]);
    }
}
