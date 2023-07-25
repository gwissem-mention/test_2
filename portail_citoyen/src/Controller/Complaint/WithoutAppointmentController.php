<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Complaint\FinalizeComplaint;
use App\Form\Model\AdditionalInformation\AdditionalInformationModel;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class WithoutAppointmentController extends AbstractController
{
    #[Route('/porter-plainte/sans-rendez-vous', name: 'complaint_without_appointment')]
    public function __invoke(SessionHandler $sessionHandler, MessageBusInterface $bus): Response
    {
        if (!$sessionHandler->getComplaint()?->getAdditionalInformation() instanceof AdditionalInformationModel) {
            return $this->redirectToRoute('home');
        }

        /** @var ComplaintModel $complaint */
        $complaint = $sessionHandler->getComplaint();

        $bus->dispatch(new FinalizeComplaint($complaint));

        return $this->redirectToRoute('complaint_end');
    }
}
