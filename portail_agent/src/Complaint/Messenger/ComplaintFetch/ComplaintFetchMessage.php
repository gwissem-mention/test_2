<?php

declare(strict_types=1);

namespace App\Complaint\Messenger\ComplaintFetch;

use App\Oodrive\DTO\Folder;

class ComplaintFetchMessage
{
    public function __construct(
        private readonly Folder $complaintFolder,
        private readonly Folder $emailFolder,
    ) {
    }

    public function getComplaintFolder(): Folder
    {
        return $this->complaintFolder;
    }

    public function getEmailFolder(): Folder
    {
        return $this->emailFolder;
    }
}
