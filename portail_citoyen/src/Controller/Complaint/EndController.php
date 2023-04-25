<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Form\Model\AdditionalInformation\AdditionalInformationModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EndController extends AbstractController
{
    #[Route('/porter-plainte/fin', name: 'complaint_end')]
    public function __invoke(SessionHandler $sessionHandler): Response
    {
        if (!$sessionHandler->getComplaint()?->getAdditionalInformation() instanceof AdditionalInformationModel) {
            return $this->redirectToRoute('home');
        }

        return $this->render('pages/complaint_end.html.twig');
    }
}
