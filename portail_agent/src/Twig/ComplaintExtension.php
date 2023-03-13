<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Complaint;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ComplaintExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('count_complaints_appointment_pending', [$this, 'getCountComplaintsAppointmentPending']),
            new TwigFilter('count_complaints_assignment_pending', [$this, 'getCountComplaintsAssignmentPending']),
            new TwigFilter('count_complaints_closed', [$this, 'getCountComplaintsClosed']),
            new TwigFilter('count_complaints_ongoing_lrp', [$this, 'getCountComplaintsOngoingLrp']),
            new TwigFilter('count_complaints_reassignment_pending', [$this, 'getCountComplaintsReassignmentPending']),
            new TwigFilter('count_complaints_unit_reassignment_pending', [$this, 'getCountComplaintsUnitReassignmentPending']),
        ];
    }

    /**
     * @param array<int, Complaint> $complaints
     */
    public function getCountComplaintsAppointmentPending(array $complaints): int
    {
        $complaintsAppointmentWaiting = 0;
        foreach ($complaints as $complaint) {
            if (Complaint::STATUS_APPOINTMENT_PENDING === $complaint->getStatus()) {
                ++$complaintsAppointmentWaiting;
            }
        }

        return $complaintsAppointmentWaiting;
    }

    /**
     * @param array<int, Complaint> $complaints
     */
    public function getCountComplaintsAssignmentPending(array $complaints): int
    {
        $complaintsAssignmentPending = 0;
        foreach ($complaints as $complaint) {
            if (Complaint::STATUS_ASSIGNMENT_PENDING === $complaint->getStatus()) {
                ++$complaintsAssignmentPending;
            }
        }

        return $complaintsAssignmentPending;
    }

    /**
     * @param array<int, Complaint> $complaints
     */
    public function getCountComplaintsClosed(array $complaints): int
    {
        $complaintsClosed = 0;
        foreach ($complaints as $complaint) {
            if (Complaint::STATUS_CLOSED === $complaint->getStatus()) {
                ++$complaintsClosed;
            }
        }

        return $complaintsClosed;
    }

    /**
     * @param array<int, Complaint> $complaints
     */
    public function getCountComplaintsOngoingLrp(array $complaints): int
    {
        $complaintsOngoing = 0;
        foreach ($complaints as $complaint) {
            if (Complaint::STATUS_ONGOING_LRP === $complaint->getStatus()) {
                ++$complaintsOngoing;
            }
        }

        return $complaintsOngoing;
    }

    /**
     * @param array<int, Complaint> $complaints
     */
    public function getCountComplaintsReassignmentPending(array $complaints): int
    {
        $complaintsReassignmentPending = 0;
        foreach ($complaints as $complaint) {
            if (Complaint::STATUS_REASSIGNMENT_PENDING === $complaint->getStatus()) {
                ++$complaintsReassignmentPending;
            }
        }

        return $complaintsReassignmentPending;
    }

    /**
     * @param array<int, Complaint> $complaints
     */
    public function getCountComplaintsUnitReassignmentPending(array $complaints): int
    {
        $complaintsUnitReassignmentPending = 0;
        foreach ($complaints as $complaint) {
            if (Complaint::STATUS_UNIT_REASSIGNMENT_PENDING === $complaint->getStatus()) {
                ++$complaintsUnitReassignmentPending;
            }
        }

        return $complaintsUnitReassignmentPending;
    }
}
