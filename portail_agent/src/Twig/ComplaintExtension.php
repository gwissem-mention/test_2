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
            new TwigFilter('count_complaints_ongoing', [$this, 'getCountComplaintsOngoing']),
        ];
    }

    /**
     * @param array<int, Complaint> $complaints
     */
    public function getCountComplaintsOngoing(array $complaints): int
    {
        $complaintsOngoing = 0;
        foreach ($complaints as $complaint) {
            if (Complaint::STATUS_ONGOING_LRP === $complaint->getStatus()) {
                ++$complaintsOngoing;
            }
        }

        return $complaintsOngoing;
    }
}
