<?php

declare(strict_types=1);

namespace App\Complaint\Messenger\SendReport;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SendReportMessage
{
    public function __construct(
        private readonly UploadedFile $report,
        private readonly int $complaintId,
    ) {
    }

    public function getReport(): UploadedFile
    {
        return $this->report;
    }

    public function getComplaintId(): int
    {
        return $this->complaintId;
    }
}
