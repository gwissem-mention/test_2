<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Complaint;
use App\Entity\Notification;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationFactory
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator,
                                private readonly TranslatorInterface $translator)
    {
    }

    public function createForComplaintAssigned(Complaint $complaint): Notification
    {
        return new Notification(
            $this->translator->trans('pel.the.declaration.has.been.assigned.to.you',
                ['declaration_number' => $complaint->getDeclarationNumber()]),
            $this->urlGenerator->generate('complaint_summary', ['id' => $complaint->getId()]));
    }

    public function createForComplaintWithViolence(Complaint $complaint): Notification
    {
        return new Notification(
            $this->translator->trans('pel.new.declaration.to.assign.with.violence',
                ['declaration_number' => $complaint->getDeclarationNumber()]),
            $this->urlGenerator->generate('complaint_summary', ['id' => $complaint->getId()]),
            true
        );
    }

    public function createForComplaintWithRobberyAndDegradation(Complaint $complaint): Notification
    {
        return new Notification(
            $this->translator->trans('pel.new.declaration.to.assign.with.robbery.and.degradation',
                ['declaration_number' => $complaint->getDeclarationNumber()]),
            $this->urlGenerator->generate('complaint_summary', ['id' => $complaint->getId()]),
            true
        );
    }

    public function createForComplaintWithStolenRegisteredVehicle(Complaint $complaint): Notification
    {
        return new Notification(
            $this->translator->trans('pel.new.declaration.to.assign.with.stolen.registered.vehicle',
                ['declaration_number' => $complaint->getDeclarationNumber()]),
            $this->urlGenerator->generate('complaint_summary', ['id' => $complaint->getId()]),
            true
        );
    }

    public function createForComplaintWithDeadlineExeeded(Complaint $complaint): Notification
    {
        return new Notification(
            $this->translator->trans('pel.declaration.deadline.reminder',
                ['declaration_number' => $complaint->getDeclarationNumber()]),
            $this->urlGenerator->generate('complaint_summary', ['id' => $complaint->getId()]),
            true
        );
    }
}
