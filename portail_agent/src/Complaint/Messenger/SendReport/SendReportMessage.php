<?php

declare(strict_types=1);

namespace App\Complaint\Messenger\SendReport;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SendReportMessage
{
    /**
     * @param array<UploadedFile> $files
     */
    public function __construct(
        private readonly array $files,
        private readonly int $complaintId,
    ) {
    }

    /**
     * @return array<UploadedFile>
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    public function getComplaintId(): int
    {
        return $this->complaintId;
    }
}
