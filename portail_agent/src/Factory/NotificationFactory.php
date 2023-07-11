<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Comment;
use App\Entity\Complaint;
use App\Entity\Notification;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationFactory
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator)
    {
    }

    public function createForComplaintAssigned(Complaint $complaint, bool $reassignment = false): Notification
    {
        return new Notification(
            $this->translator->trans(true === $reassignment ? 'pel.the.declaration.has.been.reassigned.to.you' : 'pel.the.declaration.has.been.assigned.to.you',
                ['declaration_number' => $complaint->getDeclarationNumber()]),
            $this->urlGenerator->generate('complaint_summary', ['id' => $complaint->getId()]));
    }

    public function createForComplaintUnitReassignmentOrdered(Complaint $complaint): Notification
    {
        return new Notification(
            $this->translator->trans('pel.the.unit.reassignment.of.the.declaration.need.your.approval',
                [
                    'declaration_number' => $complaint->getDeclarationNumber(),
                    'agent_identity' => $complaint->getAssignedTo()?->getAppellation(),
                ]),
            $this->urlGenerator->generate('complaint_summary', [
                'id' => $complaint->getId(),
                'showUnitReassignmentValidationModal' => 1,
            ]),
            true
        );
    }

    public function createForComplaintUnitReassignment(Complaint $complaint): Notification
    {
        return new Notification(
            $this->translator->trans('pel.the.declaration.has.been.reassigned.to.your.unit',
                ['declaration_number' => $complaint->getDeclarationNumber()]),
            $this->urlGenerator->generate('complaint_summary', ['id' => $complaint->getId()]),
            true
        );
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

    public function createForComplaintComments(Comment $comment): Notification
    {
        return new Notification(
            $this->translator->trans('pel.the.supervisor.comment.on.your.declaration',
                [
                    'declaration_number' => $comment->getComplaint()?->getDeclarationNumber(),
                    'agent_identity' => $comment->getAuthor(),
                    'agent_grade' => true === in_array('ROLE_SUPERVISOR',
                        (array) $comment->getAuthor()?->getRoles()) ? $this->translator->trans('pel.supervisor.grade') :
                        $this->translator->trans('pel.agent.grade'),
                ]),
            $this->urlGenerator->generate('complaint_summary', [
                'id' => $comment->getComplaint()?->getId(),
            ]),
            true
        );
    }

    public function createForComplaintReassignmentValidated(Complaint $complaint, User $supervisor): Notification
    {
        return new Notification(
            $this->translator->trans('pel.supervisor.has.validated.your.reassignment.request', [
                'declaration_number' => $complaint->getDeclarationNumber(),
                'user' => $supervisor->getAppellation(),
            ]),
            $this->urlGenerator->generate('home'),
        );
    }

    public function createForComplaintReassignmentRefused(Complaint $complaint, User $supervisor): Notification
    {
        return new Notification(
            $this->translator->trans('pel.supervisor.has.refused.your.reassignment.request', [
                'declaration_number' => $complaint->getDeclarationNumber(),
                'user' => $supervisor->getAppellation(),
            ]),
            $this->urlGenerator->generate('complaint_summary', ['id' => $complaint->getId()]),
        );
    }

    public function createForReminderOfClosingOfAPVInCaseOfAppointmentInPhysical(Complaint $complaint): Notification
    {
        return new Notification(
            $this->translator->trans('pel.reminder.of.closing.of.pv', [
                'declaration_number' => $complaint->getDeclarationNumber(),
            ]),
            $this->urlGenerator->generate('complaint_summary', ['id' => $complaint->getId()]),
        );
    }
}
