<?php

declare(strict_types=1);

namespace App\Report;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SendReport
{
    public function __construct(
        private readonly UploadedFile $report,
    ) {
    }

    public function getReport(): UploadedFile
    {
        return $this->report;
    }
}
