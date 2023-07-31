<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

class PreUploadNewVersionFile
{
    public const NAME = 'oodrive.file.pre_upload_new_version';

    public function __construct(
        private readonly string $fileId,
    ) {
    }

    public function getFileId(): string
    {
        return $this->fileId;
    }
}
