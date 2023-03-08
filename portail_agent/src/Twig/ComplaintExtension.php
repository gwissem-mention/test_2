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
            new TwigFilter('count_complaints_ongoing_lrp', [$this, 'getCountComplaintsOngoingLrp']),
            new TwigFilter('count_complaints_closed', [$this, 'getCountComplaintsClosed']),
        ];
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
}
