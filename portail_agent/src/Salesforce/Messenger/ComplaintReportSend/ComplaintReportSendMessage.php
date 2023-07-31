<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\ComplaintReportSend;

class ComplaintReportSendMessage
{
    public function __construct(
        private readonly int $complaintId,
        private readonly int $filesCount
    ) {
    }

    public function getComplaintId(): int
    {
        return $this->complaintId;
    }

    public function getFilesCount(): int
    {
        return $this->filesCount;
    }
}
