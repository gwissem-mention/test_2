<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\ComplaintFetch;

class ComplaintFetchMessage
{
    public function __construct(
        private readonly int $complaintId,
    ) {
    }

    public function getComplaintId(): int
    {
        return $this->complaintId;
    }
}