<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

use App\Oodrive\DTO\File;

class PostBulkUploadFile
{
    public const NAME = 'oodrive.file.post_bulk_upload';

    /**
     * @param array<File> $files
     */
    public function __construct(
        private readonly array $files,
    ) {
    }

    /**
     * @return array<File>
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
