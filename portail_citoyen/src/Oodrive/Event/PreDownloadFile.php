<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

class PreDownloadFile
{
    public const NAME = 'oodrive.file.pre_download';

    public function __construct(
        private readonly string $fileId
    ) {
    }

    public function getFileId(): string
    {
        return $this->fileId;
    }
}
