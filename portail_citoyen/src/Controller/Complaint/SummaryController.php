<?php

declare(strict_types=1);

namespace App\Controller\Complaint;

use App\Complaint\FinalizeComplaint;
use App\Form\Model\AdditionalInformation\AdditionalInformationModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/porter-plainte/recapitulatif', name: 'complaint_summary', methods: ['GET', 'POST'])]
class SummaryController extends AbstractController
{
    public function __invoke(Request $request, SessionHandler $sessionHandler, MessageBusInterface $bus): Response
    {
        if (!$sessionHandler->getComplaint()?->getAdditionalInformation() instanceof AdditionalInformationModel) {
            return $this->redirectToRoute('home');
        }

        $validationForm = $this->createFormBuilder()
            ->add('submitButton', SubmitType::class, [
                'label' => 'pel.i.confirm',
            ])->getForm();
        $validationForm->handleRequest($request);

        if ($validationForm->isSubmitted()) {
            $redirectUri = $sessionHandler->getComplaint()->isFranceConnected() ? 'complaint.end' : 'complaint.appointment';
            $bus->dispatch(new FinalizeComplaint($sessionHandler->getComplaint()));

            return new RedirectResponse($this->generateUrl($redirectUri));
        }

        return $this->render('pages/summary.html.twig', [
            'complaint' => $sessionHandler->getComplaint(),
            'validationForm' => $validationForm->createView(),
        ]);
    }
}
