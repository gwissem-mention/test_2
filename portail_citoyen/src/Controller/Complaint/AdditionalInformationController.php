<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Form\Model\Objects\ObjectsModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/porter-plainte/informations-complementaires/{noObject?0}', name: 'complaint_additional_information', requirements: ['noObject' => '0|1'], methods: ['GET'])]
class AdditionalInformationController extends AbstractController
{
    public function __invoke(
        Request $request,
        SessionHandler $sessionHandler,
        bool $noObject = false
    ): Response {
        if (!$sessionHandler->getComplaint()?->isComplaintFactsFilled()) {
            return $this->redirectToRoute('home');
        }

        if (true === $noObject) {
            $sessionHandler->setComplaint($sessionHandler->getComplaint()->setObjects(new ObjectsModel()));
        }

        if (!$sessionHandler->getComplaint()?->getObjects() instanceof ObjectsModel) {
            return $this->redirectToRoute('home');
        }

        return $this->render('pages/complaint_additional_information.html.twig', ['complaint' => $sessionHandler->getComplaint()]);
    }
}
