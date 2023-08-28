<?php

declare(strict_types=1);

namespace App\Complaint\Messenger\ComplaintObjectsFileZip;

class ComplaintObjectsFileZipMessage
{
    public function __construct(
        private readonly int $complaintId
    ) {
    }

    public function getComplaintId(): int
    {
        return $this->complaintId;
    }
}
