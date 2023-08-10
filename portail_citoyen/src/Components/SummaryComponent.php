<?php

declare(strict_types=1);

namespace App\Components;

use App\Form\Model\AppointmentModel;
use App\Form\SummaryType;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('summary')]
class SummaryComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    public ComplaintModel $complaint;

    public function __construct(private readonly SessionHandler $sessionHandler)
    {
        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();
        $this->complaint = $complaint;
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            SummaryType::class,
            $this->sessionHandler->getComplaint()?->getAppointment()
        );
    }

    #[LiveAction]
    public function submit(): RedirectResponse
    {
        if (true === $this->complaint->isAppointmentRequired()) {
            return $this->redirectToRoute('complaint_appointment');
        }

        $this->submitForm();

        /** @var AppointmentModel $appointment */
        $appointment = $this->getFormInstance()->getData();
        $this->complaint->setAppointment($appointment);
        $this->sessionHandler->setComplaint($this->complaint->setAppointment($appointment));

        return $appointment->isAppointmentAsked() ? $this->redirectToRoute('complaint_appointment') : $this->redirectToRoute('complaint_without_appointment');
    }
}
