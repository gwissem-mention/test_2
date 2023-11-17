<?php

declare(strict_types=1);

namespace App\Components;

use App\Complaint\FinalizeComplaint;
use App\Form\AppointmentType;
use App\Form\Model\AppointmentModel;
use App\Referential\Entity\Unit;
use App\Referential\Repository\UnitRepository;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('appointment')]
class AppointmentComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?string $unitSelected = null;

    public function __construct(private readonly SessionHandler $sessionHandler)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            AppointmentType::class,
            $this->sessionHandler->getComplaint()?->getAppointment()
        );
    }

    #[LiveAction]
    public function submit(MessageBusInterface $bus, UnitRepository $unitRepository): RedirectResponse
    {
        $this->submitForm();

        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();
        /** @var AppointmentModel $appointment */
        $appointment = $this->getFormInstance()->getData();
        $complaint->setAppointment($appointment);

        if ($this->unitSelected) {
            /** @var ?Unit $unit */
            $unit = $unitRepository->findOneBy(['idAnonym' => $this->unitSelected]);
            if ($unit instanceof Unit) {
                $complaint
                    ->setAffectedService($unit->getServiceId())
                    ->setAffectedServiceInstitution($unit->getInstitutionCode()?->value);
            }
        }

        $this->sessionHandler->setComplaint($complaint);

        $bus->dispatch(new FinalizeComplaint($complaint));

        return $this->redirectToRoute('complaint_end');
    }
}
